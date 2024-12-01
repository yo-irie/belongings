<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/functions.php';
	require_once '../belongings/classes/UserLogic.php';
	if(isset($_SESSION['reg'])) $err['reg'] = $_SESSION['reg'];
	if(isset($_SESSION['match'])) $err['match'] = $_SESSION['match'];
	unset($_SESSION['reg']);
	unset($_SESSION['match']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員登録</title>
</head>
<body>
<h2>登録フォーム</h2>
	<form action=<?php echo "http://" . h($_SERVER['HTTP_HOST']) . "/signup/"; ?> method="POST">
		<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
		<input type="hidden" name="register_token" value="<?php echo h($_SESSION['register_token']);?>">
		<p>
			<label for="name">名前</label>
			<input type="text" id="name" name="name" required>
		</p>
		<p>
			<label for="password">パスワード</label>
			<input type="password" id="password" name="password" required>
			<?php if(isset($err['reg'])) {
					echo "<p>" . $err['reg'] . "</p>";
			} ?>
		</p>
		<p>
			<label for="password_conf">パスワード確認</label>
			<input type="password" id="password_conf" name="password_conf" required>
			<?php if(isset($err['match'])) {
				echo "<p>" . $err['match'] . "</p>";
			} ?>
		</p>
		<p><input type="submit" value="登録"></p>
	</form>
</body>
</html>