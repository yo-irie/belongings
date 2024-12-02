<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/classes/UserLogic.php';

	//不正な遷移でアクセスした場合の処理
	if (!$logout = filter_input(INPUT_POST, 'logout')) {
		exit('不正なリクエストです');
	}
	//ログインしているか判定、セッションが切れていたらログインを促す
	$result = UserLogic::checkLogin();
	if(!$result) {
		exit('セッションが切れたのでログインしてください');
	}

	//ログアウトする処理
	UserLogic::logout();
	header('Location: https://' . $_SERVER['HTTP_HOST'] . '/user/logout_comp/');
	exit;
?>