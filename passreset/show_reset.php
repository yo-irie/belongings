<?php
	if (session_status() == PHP_SESSION_NONE) session_start();//セッションスタートしてからファイルを読み込む
	require_once '../public_html/functions.php';
	require_once '../public_html/classes/UserLogic.php';
	$pdo = connect();
	//reset用のトークン(URLのクエリから取得)
	$resetToken = filter_input(INPUT_GET, 'token');
	if (empty($_SESSION['reset_token'])) {
		$_SESSION['reset_token'] = $resetToken;
	}
	//$_SESSION['registerToken'] = $registerToken;

	$sql = 'SELECT * FROM password_resets WHERE token = :token';

	try {
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':token', $resetToken, PDO::PARAM_STR);
		$stmt->execute();

		$user = $stmt->fetch();
		if (!$user) exit('無効なURLです');
	} catch (PDOException $e) {
		echo '接続に失敗 :' . $e->getMessage();
	}

	//トークンの有効期限 24時間
	$tokenValid = (new \DateTime('Asia/Tokyo'))->modify("-24 hour")->format("Y-m-d H:i:s");
	//仮登録が24時間以上前なら有効期限切れとする
	if ($user['token_created_at'] < $tokenValid) exit('有効期限切れです');

	//本登録用のトークンが空なら再生成
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));;
	}

	//エラーがなければ本登録画面を表示
	header('Location: https://'. $_SERVER['HTTP_HOST'] .'/reset_form/');

?>