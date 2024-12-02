<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../belongings/dbconnect.php';
	require_once '../belongings/classes/UserLogic.php';
	$request = filter_input_array(INPUT_POST);

	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}

	//エラーがなければユーザーデータを更新
	$result = UserLogic::deleteUser($_SESSION['login_user']['email']);
	if (!$result) exit('アカウント削除に失敗しました');
	UserLogic::logout();
	echo 'アカウントを削除しました';
	echo "<a href='https://" . $_SERVER['HTTP_HOST'] . "'> ログイン画面へ</a>";
?>