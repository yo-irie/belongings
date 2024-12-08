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
	$csrf_token = setToken();
	$_SESSION['csrf_token'] = $csrf_token;
	$categorylist = BelongingLogic::getCategoryList();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>持ち物追加</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
	integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="border col-7 m-5">
		<form action=<?php echo "https://". h($_SERVER['HTTP_HOST']) . "/user/bg/add/"; ?> method="POST">
			<br>
			<div class="mx-3">
				<div class="h2">持ち物を追加する</div>
			</div>
			<br>
			<div class="form-group">
				<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
				<div class="mx-3">
					<label for="select_ategory" class="form-label">カテゴリーを選択</label>
					<select name="select_category" class="form-select">
						<?php for ($i=0; $i < count($categorylist); $i++) : ?>
							<option value="<?php echo $categorylist[$i]['category_name_en']; ?>"><?php echo $categorylist[$i]['category_name']; ?></option>
						<?php endfor; ?>
					</select>
				</div>
				<br>
				<div class="mx-3">
					<label for="bgname" class="form-label">登録する持ち物を入力</label>
					<input type="text" name="belonging_name" required class="form-control"><br>
				</div>
				<div class="mx-3">
					<label for="note" class="form-label">メモを入力</label>
					<textarea name="note" class="form-control"></textarea><br>
				</div>
				<div class="mx-3">
					<label for="expiry">使用期限を入力</label>
					<input type="date" name="expiry_date" min="2024-01-01" max="9999-12-31"><br>
				</div>
				<div class="row col-5 m-3">
					<input type="submit" name="add" value="追加" class="btn btn-primary"> 
				</div>
			</div>
		</form>
		<a href=<?php echo "https://". $_SERVER['HTTP_HOST'] . "/user/"; ?>>トップに戻る<br></a>
	</div>
</body>
</html>