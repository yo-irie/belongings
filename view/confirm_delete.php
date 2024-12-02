<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/functions.php';
	require_once '../belongings/classes/UserLogic.php';
	require_once '../belongings/classes/BelongingLogic.php';
	//ログインしているか判定し、していなければログイン画面へ
	$result = UserLogic::checkLogin();
	if(!$result) {
		$_SESSION['login_err'] = 'ログインしてください';
		header("Location: https://" . $_SERVER["HTTP_HOST"]);
		exit;
	}
	//CSRF対策トークン生成
	$csrf_token = setToken();
	$_SESSION['csrf_token'] = $csrf_token;
	//ログインしているユーザー名表示用
	$login_user = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>アカウント編集</title>
</head>
<body>
	<h2></h2>
	<p><?php echo h($login_user['name']); ?>さんのアカウントを削除します。</p>

	<p>よろしければ「削除」をクリックしてください。</p>
	<form action=<?php echo "https://". h($_SERVER['HTTP_HOST']) . "/user/account/delete/"; ?> method="POST">
		<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
		<input type="submit" name="delete" value="削除"> 
	</form>

	<a href=<?php echo "https://". h($_SERVER['HTTP_HOST']) . "/user/account/"; ?>>アカウント管理ページに戻る<br></a>
</body>
</html>