<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../public_html/functions.php';
	require_once '../public_html/classes/UserLogic.php';
	require_once '../public_html/classes/BelongingLogic.php';
	//ログインしているか判定し、していなければログイン画面へ
	$isResult = UserLogic::checkLogin();
	if(!$isResult) {
		$_SESSION['login_err'] = 'ログインしてください';
		header("Location: https://" . $_SERVER["HTTP_HOST"]);
		exit;
	}
	//ログインしているユーザー名表示用
	$login_user = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>アカウント管理</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
	integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="container">
		<div class="h2 mb-4 text-success"><?php echo h($login_user['name']); ?>さんのアカウント管理ページです</div>
		<ul class="list-group mb-3">
			<li class="list-group-item"><a href=<?php echo (empty($_SERVER['HTTPS']) ? 'https://' : 'https://') . $_SERVER['HTTP_HOST'] . "/user/account/account_edit_dis/"; ?>>アカウント情報を編集する<br></a></li>
			<li class="list-group-item"><a href=<?php echo (empty($_SERVER['HTTPS']) ? 'https://' : 'https://') . $_SERVER['HTTP_HOST'] . "/user/account/confirm_delete/"; ?>>アカウントを削除する<br></a></li>
		</ul>
		<a href=<?php echo (empty($_SERVER['HTTPS']) ? 'https://' : 'https://') . $_SERVER['HTTP_HOST'] . "/user/"; ?>>マイページに戻る<br></a>
	</div>
</body>
</html>