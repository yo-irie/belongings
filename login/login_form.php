<?php
	if (session_status() == PHP_SESSION_NONE) session_start();

	//ログインしていたらマイページにログイン
	require_once '../belongings/classes/UserLogic.php';
	require_once '../belongings/functions.php';
	$result = UserLogic::checkLogin();
	if ($result){
		header("Location: http://" . $_SERVER["HTTP_HOST"] . "/user/");
		return;
	}

	$err = $_SESSION;
	//ログインしていない場合
	$login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
	unset($_SESSION['login_err']);

	$_SESSION = array();
	session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ログイン画面</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="m-5 h2">ログインフォーム</div>

	<div class="container">
		<?php echo $login_err; ?>
		<?php if(isset($err['msg'])) {
			echo "<div class='form-text text-danger mb-2'>" . $err['msg'] . "</div>";
		} ?>
		<div class="col-6">
			<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/login/"; ?> method="POST">
				<div class="mb-2">
					<label class="form-label" for="email">メールアドレス</label>
					<input type="email" class="form-control" name="email" required>
					<?php if(isset($err['email_empty'])) {
						echo "<div class='form-text'>" . $err['email_empty'] . "</div>";
					} ?>
				</div>
				
				<div class="mb-2">
					<label class="form-label" for="password">パスワード</label>
					<input type="password" class="form-control" name="password" required>
					<?php if(isset($err['password_empty'])) {
						echo "<div class='form-text'>" . $err['password_empty'] . "</div>";
					} ?>
				</div>
				<p>
					<input type="submit" class="btn btn-primary" value="ログイン">
				</p>
			</form>
		</div> 
		<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/resetrequest/"; ?>>パスワードをお忘れの場合<br></a>
		<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/signuprequest/"; ?>>新規登録はこちら</a>
	</div>
</body>
</html>