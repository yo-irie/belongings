<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	require_once '../public_html/dbconnect.php';
	require_once '../public_html/classes/UserLogic.php';
	$request = filter_input_array(INPUT_POST);

	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}

	//エラーがなければユーザーデータを更新
	$isResult = UserLogic::updateProfile($request);
	if (!$isResult) exit('ユーザー情報更新に失敗しました');

	//ユーザー情報を再取得する
	$login_user = UserLogic::getUserByEmail($request['email']);
	$_SESSION['login_user'] = $login_user;

	echo 'ユーザー情報を更新しました';
	echo "<a href='https://" . $_SERVER['HTTP_HOST'] . "/user/'> トップ画面へ</a>";
?>