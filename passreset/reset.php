<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/dbconnect.php';
	$pdo = connect();

	$request = filter_input_array(INPUT_POST);

	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
			exit('不正なリクエストです');
	}
	$sql = 'SELECT * FROM password_resets WHERE token = :token';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':token', $request['reset_token'], PDO::PARAM_STR);
	$stmt->execute();
	$resetUser = $stmt->fetch();

	if (!$resetUser) exit('無効なURLです');

	$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}$/';
	$err = [];

	if (!preg_match($pattern, $request['password'])) {
		$err['reg'] = "パスワードは大小英数字をそれぞれ最低1文字以上含め、8文字以上100文字以内にしてください";
	}
	if ($request['password'] !== $request['password_conf']) {
		$err['match'] = "確認パスワードが間違っています";
	}
	if (count($err) > 0) {
		//エラーがあれば戻す
		$_SESSION['reg'] = $err['reg'];
		$_SESSION['match'] = $err['match'];
		foreach ($request as $key => $value) {
				$_SESSION["{$key}"] = $value;
		}
		header('Location: https://' . $_SERVER['HTTP_HOST'] . '/reset_form/');
		return;
	}

	//エラーがなければユーザーデータを更新 
	try {
		$pdo->beginTransaction();
		$sql = 'UPDATE users SET password = :password WHERE email = :email';
		$hashedPassword = password_hash($request['password'], PASSWORD_BCRYPT);
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':password', $hashedPassword, \PDO::PARAM_STR);
		$stmt->bindValue(':email', $resetUser['email'], \PDO::PARAM_STR);
		$stmt->execute();

	//password_resetsテーブルから削除
		$sql = 'DELETE FROM password_resets WHERE email = :email';
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':email', $resetUser['email'], \PDO::PARAM_STR);
		$stmt->execute();

		$pdo->commit();
} catch (Exception $e) {
		$pdo->rollBack();
		exit($e->getMessage());
	}

	$html = <<<EOD
	<!DOCTYPE html>
	<html lang="ja">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>パスワード再設定完了</title>
	</head>
	<body>
		<h2>パスワードの再設定を完了しました</h2>
		<a href="https://{$_SERVER['HTTP_HOST']}/">ログイン画面に戻る</a>
	</body>
	</html>
	EOD;

	echo $html;
?>