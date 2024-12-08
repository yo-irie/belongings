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
	$categorylist = BelongingLogic::getCategoryList();
	$user_wish = BelongingLogic::getUserWishByuserid($login_user['id']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ウィッシュリスト</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<div class="container">
		<div class="h2 mb-4 text-success"><?php echo h($login_user['name']); ?>さんのウィッシュリスト</div>
			<div class="accordioncategory">
				<?php for ($i=0; $i < count($categorylist); $i++) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id=<?php echo '"'. "heading" . $i . '"';?>>
							<button class="accordion-button collapsed" type="button"
							data-bs-toggle="collapse" data-bs-target=<?php echo '"#'. "collapse" . $i . '"';?>
							aria-expanded="false" aria-controls=<?php echo '"'. "collapse" . $i . '"';?>>
								<?php echo h($categorylist[$i]['category_name']); ?><!--カテゴリーリスト表示 -->
							</button>
						</h2>
						<div id=<?php echo '"'. "collapse" . $i . '"';?>
							class="accordion-collapse collapse"
							aria-labelledby=<?php echo '"'. "collapse" . $i . '"';?> 
							data-bs-parent="#accordioncategory">
							<?php for ($j=0; $j < count($user_wish); $j++) : ?><!--カテゴリーIDが合致したら名前を表示する-->
								<?php if ($i+1 === $user_wish[$j]['category_id']) :  $belongingid = $user_wish[$j]['user_belonging_id']; ?>
									<div class="accordion-body">
										<a href=<?php echo "https://". $_SERVER['HTTP_HOST'] . "/user/wish/wish_detail?b={$belongingid}"; ?>><?php echo $user_wish[$j]['belonging_name'] . '<br>';?></a>
									</div>
								<?php endif; ?>
							<?php endfor; ?>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		<div class="btn btn-warning mt-3 mb-3">
			<a href=<?php echo "https://". $_SERVER['HTTP_HOST'] . "/user/wish/add_page/"; ?>>ウィッシュリスト追加</a>
		</div>
		<br>
		<a href=<?php echo "https://". $_SERVER['HTTP_HOST'] . "/user/"; ?>>トップ画面に戻る<br></a>
	</div>
</body>
</html>