<?php
	$err = [];//エラーメッセージ格納用の配列
	if (session_id() === '') session_start();//セッションスタートしてからファイルを読み込む
	require_once '../belongings/classes/UserLogic.php';

	//バリデーション
	if (!$email = filter_input(INPUT_POST, 'email')) {
		$err['email_empty'] = 'メールアドレスを入力してください';
	}
	if (!$password = filter_input(INPUT_POST, 'password')) {
		$err['password_empty'] = 'パスワードを入力してください';
	}

	if (count($err) > 0) {
		//エラーがあれば戻す
		$_SESSION = $err;
		header('Location: http://' . $_SERVER['HTTP_HOST']);
		return;
	}
	
	$result = UserLogic::login($email, $password);
	if (!$result) {
		header('Location: http://' . $_SERVER['HTTP_HOST']);
		return;
	}
	//エラーがなければログイン後の画面に遷移
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/user/');
?>