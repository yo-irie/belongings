<?php
	if (session_id() === '') session_start();

	require_once '../public_html/classes/UserLogic.php';
	require_once '../public_html/classes/BelongingLogic.php';
	//ログインしているか判定し、していなければログイン画面へ
	$isResult = UserLogic::checkLogin();
	if(!$isResult) {
		$_SESSION['login_err'] = 'ログインしてください';
		header("Location: https://" . $_SERVER["HTTP_HOST"]);
		exit;
	}
	//データ受け取り
	$request = filter_input_array(INPUT_POST);
	//CSRFトークンが不正ならば処理を中断する
	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}
	//削除する持ち物作成者がログイン中のユーザーじゃなかったら不正とする
	$user_belonging = BelongingLogic::getUserBelongingByuserid($_SESSION['loging_user']['id']);
	$select_by_belongingid = BelongingLogic::getUserBelongingBybelongingid($request);
	if ($select_by_belongingid['user_id'] !== $_SESSION['loging_user']['id']) {
		exit('不正なリクエストです');
	}
	//エラーがなければユーザーデータを更新
	$isResult = BelongingLogic::deleteBelonging($request['belongingid']);
	if (!$isResult) {
		exit('持ち物削除に失敗しました');
	}

	header('Location: https://'. $_SERVER['HTTP_HOST'] .'/user/wish/');
	exit;

?>