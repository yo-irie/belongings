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
	//URLクエリからデータ取得(b=持ち物ID)
	$request = intval(filter_input(INPUT_GET, 'b'));

	$login_user = $_SESSION['login_user'];
	$user_wish = BelongingLogic::getUserWishByuserid($login_user['id']);
	$categorylist = BelongingLogic::getCategoryList();
	//user_belongin_id=$requestなのでターゲットの持ち物情報を特定する
	$select_by_belongingid = BelongingLogic::getUserBelongingBybelongingid($request);
	//URLを直接された場合の対策、持ち物を追加したユーザーとログインしたユーザーが異なる場合は終了する
	if ($select_by_belongingid['user_id'] !== $login_user['id']) {
		exit('不正なリクエストです');
	}
	//date型データが'0000-00-00'なら空欄にする
	if ($select_by_belongingid['release_date'] == '0000-00-00') $select_by_belongingid['release_date'] = "";
	//CSRFトークンが空であればセッション変数に代入する
	if (empty($_SESSION['csrf_token'])) {
		$csrf_token = bin2hex(random_bytes(32));
		$_SESSION['csrf_token'] = $csrf_token;
	}
	//カテゴリー名称表示用
	$category_name = BelongingLogic::getCategorynameBybelongingid($request);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>欲しい物詳細</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="container">
	<div class="h2 mb-3 mt-3">欲しい物詳細</div>
		<div class="card" style="width: 18rem;">
		<div class="h4 card-title m-3"><?php echo h($category_name['category_name']); ?></div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item">名称：<?php echo h($select_by_belongingid['belonging_name']); ?><br></li>
				<li class="list-group-item">メモ：<?php echo h($select_by_belongingid['note']); ?><br></li>
				<li class="list-group-item">リリース日：<?php echo h($select_by_belongingid['expiry_date']); ?></li>
			</ul>
		</div>

		<!--編集処理-->
		<div class="border col-5 mt-5">
		<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/wish/update/"; ?> method="POST">
			<br>
			<div class="mx-3">
				<div class="h2">データを更新する</div>
			</div>
			<br>
			<div class="form-group">
				<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
				<!--更新する持ち物IDを送信-->
				<input type="hidden" name="belongingid" value="<?php echo h($request); ?>">
				<div class="mx-3">
					<label for="select_ategory" class="form-label">カテゴリーを選択</label>
					<select name="select_category" class="form-select">
						<?php for ($i=0; $i < count($categorylist); $i++) : ?>
							<option value="<?php echo h($categorylist[$i]['category_name_en']); ?>"><?php echo h($categorylist[$i]['category_name']); ?></option>
						<?php endfor; ?>
					</select>
				</div><br>
				<div class="mx-3">
					<label for="bgname" class="form-label">欲しい物の名称を更新</label>
					<input type="text" name="belonging_name" value=<?php echo h($select_by_belongingid['belonging_name']); ?> required class="form-control"><br>
				</div>
				<div class="mx-3">
					<label for="note" class="form-label">メモを更新</label>
					<textarea name="note" class="form-control"><?php echo h($select_by_belongingid['note']); ?></textarea><br>
				</div>
				<div class="mx-3">
					<label for="expiry" class="form-label">リリース日を更新</label>
					<input type="date" name="expiry_date" min="2024-01-01" max="9999-12-31" ><br>
				</div>
				<div class="row col-5 m-3">
					<input type="submit" name="update" value="更新する" class="btn btn-primary"> 
				</div>
			</div>
			</form>
		</div>


		<!--削除処理-->
		<div class="border col-5 mt-5">
			<div class="mx-3 mt-3"><h2>データを削除する</h2></div>
			<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/wish/delete/"; ?> method="POST">
				<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
				<!--削除する持ち物IDを送信-->
				<input type="hidden" name="belongingid" value="<?php echo h($request); ?>">
				<div class="row col-5 m-3">
					<input type="submit" name="delete" value="削除する" class="btn btn-danger">
				</div>
			</form>
		</div>
		<div class="my-3">
			<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/wish/"; ?>>欲しい物リストへ<br></a>
		</div>
</body>
</html>