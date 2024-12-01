<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/functions.php';
	require_once '../belongings/classes/UserLogic.php';
	require_once '../belongings/classes/BelongingLogic.php';
	//ログインしているか判定し、していなければログイン画面へ
	$result = UserLogic::checkLogin();
	if(!$result) {
		$_SESSION['login_err'] = 'ログインしてください';
		header("Location: http://" . $_SERVER["HTTP_HOST"]);
		return;
	}
	//ログインしているユーザー名表示用
	$login_user = $_SESSION['login_user'];
	$categorylist = BelongingLogic::getCategoryList();
	$user_belonging = BelongingLogic::getUserBelongingByuserid($login_user['id']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>マイページ</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="container">
	<div class="h2 mb-4 text-success"><?php echo h($login_user['name']); ?>さんのページです</div>
		<ul class="list-group">
			<li class="list-group-item"><a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/bg/"; ?>>持ち物リスト<br></a></li>
			<li class="list-group-item"><a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/wish/"; ?>>ウィッシュリスト<br></a></li>
			<li class="list-group-item"><a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/account/"; ?>>アカウント管理<br></a></li>
		</ul>
		<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/logout/"; ?> method="POST">
			<input type="submit" name="logout" class="btn btn-primary btn-sm mt-3" value="ログアウト"> 
		</form>
	</div>
</body>
</html>