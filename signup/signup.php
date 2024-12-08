<?php
	if (session_id() === '') session_start();

	$request = filter_input_array(INPUT_POST);

	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}

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
		header('Location: https://' . $_SERVER['HTTP_HOST'] . '/signup_form/');
		return;
	}

	//エラーがなければユーザーデータを更新
	require_once '../public_html/dbconnect.php';
	
	try {
		$pdo = connect();
		$sql = 'UPDATE users SET name=:name, password=:password, register_token_verified_at=:register_token_verified_at, status=:status WHERE register_token=:register_token';
		$hashedPassword = password_hash($request['password'], PASSWORD_BCRYPT);
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':name', $request['name'], \PDO::PARAM_STR);
		$stmt->bindValue(':password', $hashedPassword, \PDO::PARAM_STR);
		$stmt->bindValue(':register_token_verified_at', (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
		$stmt->bindValue(':status', 'main', \PDO::PARAM_STR);
		$stmt->bindValue(':register_token', $request['register_token'], \PDO::PARAM_STR);
		$stmt->execute();
	} catch (Exception $e) {
		echo $e->getMessage();
		exit();
	}

	$html = <<<EOD
	<!DOCTYPE html>
	<html lang="ja">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>登録完了</title>
	</head>
	<body>
		<h2>ユーザー登録を完了しました</h2>
		<a href="https://{$_SERVER['HTTP_HOST']}/">ログイン画面に戻る</a>
	</body>
	</html>
	EOD;

	echo $html;
?>