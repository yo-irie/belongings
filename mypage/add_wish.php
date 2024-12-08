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

	$request = filter_input_array(INPUT_POST);
	//CSRFトークンが不正ならば処理を中断する
	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}
	//カテゴリーID取得用
	$category = BelongingLogic::getCategoryByName($request['select_category']);
	//INSERT用データの配列作成
	$belongingData = array('user_id' => $_SESSION['login_user']['id']);
	$belongingData['category_id'] = $category['category_id'];
	$belongingData['belonging_name'] = $request['belonging_name'];
	$belongingData['note'] = $request['note'];
	$belongingData['release_date'] = $request['release_date'];
	//エラーがなければユーザーデータを更新
	$isResult = BelongingLogic::createWish($belongingData);
	if (!$isResult) {
		exit('ウィッシュリスト編集に失敗しました');
	}

	header('Location: https://'. $_SERVER['HTTP_HOST'] .'/user/wish/');
	exit;

?>