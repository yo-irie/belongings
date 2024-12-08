<?php
	require_once './functions.php';
	$path = cleanHostString($_SERVER['REQUEST_URI']);

	if ($path === '/') {
		require "../public_html/login/login_form.php";
	} else if ($path === '/signuprequest/') {
		//アカウント作成リクエスト
		require "../public_html/view/signup_request.php";
	} else if ($path === '/signup_tmp/') {
		//アカウント作成メール送信処理
		require "../public_html/signup/tmp_signup.php";
	} else if ($path === '/signup_show/') {
		//アカウント作成画面表示処理
		require "../public_html/signup/show_signup.php";
	} else if ($path === '/signup_form/') {
		//アカウント作成画面
		require "../public_html/view/signup_form.php";
	} else if ($path === '/signup/') {
		//アカウント作成処理
		require "../public_html/signup/signup.php";
	} else if ($path === '/resetrequest/') {
		//パスワード再設定リクエスト
		require "../public_html/view/reset_request.php";
	} else if ($path === '/reset_tmp/') {
		//パスワード再設定メール送信処理
		require "../public_html/passreset/tmp_reset.php";
	} else if ($path === '/reset_show/') {
		//パスワード再設定画面を表示する処理
		require "../public_html/passreset/show_reset.php";
	} else if ($path === '/reset_form/') {
		//パスワード再設定画面
		require "../public_html/view/reset_form.php";
	} else if ($path === '/reset/') {
		//パスワード再設定処理
		require "../public_html/passreset/reset.php";
	} else if ($path === '/login/') {
		//ログイン処理
		require "../public_html/login/login.php";
	} else if ($path === '/user/') {
		//マイページトップ画面
		require "../public_html/view/mypage.php";
	} else if ($path === '/user/logout/') {
		//ログアウト処理
		require "../public_html/mypage/logout.php";
	} else if ($path === '/user/logout_comp/') {
		//ログアウト完了画面
		require "../public_html/view/logout_comp.php";
	} else if ($path === '/user/account/') {
		//アカウント編集トップ画面
		require "../public_html/view/account_manage.php";
	} else if ($path === '/user/account/account_edit_dis/') {
		//アカウント編集画面
		require "../public_html/view/account_edit_dis.php";
	} else if ($path === '/user/account/edit/') {
		//アカウント編集処理
		require "../public_html/mypage/account_edit.php";
	} else if ($path === '/user/account/passedit/') {
		//パスワード変更処理
		require "../public_html/mypage/account_passedit.php";
	} else if ($path === '/user/account/confirm_delete/') {
		//アカウント削除確認画面
		require "../public_html/view/confirm_delete.php";
	} else if ($path === '/user/account/delete/') {
		//アカウント削除処理
		require "../public_html/mypage/account_delete.php";
	} else if ($path === '/user/bg/') {
		//持ち物リスト画面
		require "../public_html/view/belonging_list.php";
	} else if ($path === '/user/bg/add_page/') {
		//持ち物追加画面
		require "../public_html/view/belonging_add_page.php";
	} else if ($path === '/user/bg/add/') {
		//持ち物追加処理
		require "../public_html/mypage/add_belongings.php";
	} else if ($path === '/user/bg/bg_detail/') {
		//持ち物詳細画面
		require "../public_html/view/belonging_detail.php";
	} else if ($path === '/user/bg/update/') {
		//持ち物編集画面
		require "../public_html/mypage/update_belongings.php";
	} else if ($path === '/user/bg/delete/') {
		//持ち物削除処理
		require "../public_html/mypage/delete_public_html.php";
	} else if ($path === '/user/wish/') {
		//欲しい物リスト画面
		require "../public_html/view/wish_list.php";
	} else if ($path === '/user/wish/add_page/') {
		//持ち物追加画面
		require "../public_html/view/wish_add_page.php";
	} else if ($path === '/user/wish/add/') {
		//持ち物追加処理
		require "../public_html/mypage/add_wish.php";
	} else if ($path === '/user/wish/wish_detail/') {
		//欲しいもの詳細画面
		require "../public_html/view/wish_detail.php";
	} else if ($path === '/user/wish/update/') {
		//欲しい物編集処理
		require "../public_html/mypage/update_wish.php";
	} else if ($path === '/user/wish/delete/') {
		//欲しいもの削除処理
		require "../public_html/mypage/delete_wish.php";
	} else {
		header("HTTP/1.1 404 Not Found");
	}