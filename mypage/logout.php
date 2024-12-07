<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/classes/UserLogic.php';

	$request = filter_input_array(INPUT_POST);
	//不正な遷移でアクセスした場合の処理
	if (
    empty($request['csrf_token'])
    || empty($_SESSION['csrf_token'])
    || $request['csrf_token'] !== $_SESSION['csrf_token']
	) {
			exit('不正なリクエストです');
	}

	//ログインしているか判定、セッションが切れておりクッキーもなければログインを促す
	$result = UserLogic::checkLogin();
	if(!$result) {
		exit('セッションが切れたのでログインしてください');
	}

	//ログアウトする処理
	UserLogic::logout();
	header('Location: https://' . $_SERVER['HTTP_HOST'] . '/user/logout_comp/');
	exit;
?>