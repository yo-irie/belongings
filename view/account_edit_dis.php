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
	//パスワードチェックした結果エラーがあった場合
	if(isset($_SESSION['current'])) $err['current'] = $_SESSION['current'];
	if(isset($_SESSION['reg'])) $err['reg'] = $_SESSION['reg'];
	if(isset($_SESSION['match'])) $err['match'] = $_SESSION['match'];
	unset($_SESSION['current']);
	unset($_SESSION['reg']);
	unset($_SESSION['match']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>アカウント編集</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
	integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="m-5 h2"><?php echo h($login_user['name']); ?>さんのアカウントの編集ページです</div>
	<div class="border col-5 m-5">
		<form action=<?php echo "https://". h($_SERVER['HTTP_HOST']) . "/user/account/edit/"; ?> method="POST">
			<br>
			<div class="mx-3">
				<div class="h5">【名前・Eメール変更】以下のフォームに入力して編集をクリックしてください</div>
			</div>
			<br>
			<div class="form-group">
				<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
				<input type="hidden" name="login_name" value="<?php echo h($login_user['name']);?>">
				<div class="mx-3">
					<label for="name">名前を変更</label>
					<input type="text" name="name" value=<?php echo h($login_user['name']); ?> required class="form-control mb-3">
				</div>
				<div class="mx-3">
					<label for="email">Eメールを変更</label>
					<input type="text" name="email" value=<?php echo h($login_user['email']); ?> required class="form-control mb-3">
				</div>
				<div class="row col-5 m-3">
					<input type="submit" name="edit" value="編集" class="btn btn-primary"> 
				</div>
			</div>
		</form>
	</div>

	<div class="border col-5 m-5">
		<form action=<?php echo "https://". h($_SERVER['HTTP_HOST']) . "/user/account/passedit/"; ?> method="POST">
			<br>
			<div class="mx-3">
				<div class="h5">【パスワード変更】以下のフォームに入力してパスワード変更をクリックしてください</div>
			</div>
			<br>
			<div class="form-group">
				<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
				<div class="mx-3">
					<label for="password_current">現在のパスワード</label>
					<input type="password" id="password_current" name="password_current" required class="form-control mb-3">
						<?php if(isset($err['current'])) {
							echo "<div class='text-danger'>" . $err['current'] . "</div>";
						} ?>
				</div>
				<div class="mx-3">
					<label for="password">変更するパスワード</label>
					<input type="password" id="password" name="password" required class="form-control mb-3">
						<?php if(isset($err['reg'])) {
							echo "<div class='text-danger'>" . $err['reg'] . "</div>";
						} ?>
				</div>
				<div class="mx-3">
					<label for="password_conf">パスワード確認</label>
						<input type="password" id="password_conf" name="password_conf" required class="form-control mb-3">
							<?php if(isset($err['match'])) {
								echo "<div class='text-danger'>" . $err['match'] . "</div>";
							} ?>
				</div>
				<div class="row col-5 m-3">
					<input type="submit" value="パスワード変更" class="btn btn-primary">
				</div>
			</div>
		</form>
	</div>
	<div class="m-5">
		<a href=<?php echo (empty($_SERVER['HTTPS']) ? 'https://' : 'https://') . h($_SERVER['HTTP_HOST']) . "/user/account/"; ?>>アカウント管理ページに戻る<br></a>
	</div>
</body>
</html>