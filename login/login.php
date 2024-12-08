<?php
	$err = [];//エラーメッセージ格納用の配列
	if (session_id() === '') session_start();//セッションスタートしてからファイルを読み込む
	require_once '../public_html/classes/UserLogic.php';

	$request = filter_input_array(INPUT_POST);
	//CSRFトークンが不正ならば処理を中断する
	if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
		exit('不正なリクエストです');
	}

	$email = $request['email'];
	$password = $request['password'];
	//バリデーション
	if (!$email) {
		$err['email_empty'] = 'メールアドレスを入力してください';
	}
	if (!$password) {
		$err['password_empty'] = 'パスワードを入力してください';
	}

	if (count($err) > 0) {
		//エラーがあれば戻す
		$_SESSION = $err;
		header('Location: https://' . $_SERVER['HTTP_HOST']);
		return;
	}
	//ログイン処理
	$isResult = UserLogic::login($email, $password);
	if (!$isResult) {
		header('Location: https://' . $_SERVER['HTTP_HOST']);
		return;
	}
	//エラーがなく、自動ログインにチェックが入っていなければログイン後の画面に遷移
	if (empty($request['remember'])) {
		header('Location: https://' . $_SERVER['HTTP_HOST'] . '/user/');
		exit;	
	}
	//以下、自動ログインにチェックが入っている場合の処理
	$remember_token = bin2hex(random_bytes(32));
	//remember_tokenをデータベースに書き込む
	$isResultRemember = UserLogic::setRememberToken($remember_token, $email);

	if (!$isResultRemember) {
		exit('次回ログイン省略処理に失敗しました');
	} else if ($remember_token) {
		// cookieのオプション
		$options = [
			'expires' => time() + 60 * 60 * 24 * 7, // cookieの有効期限を7日間に設定
			'path' => '/', // 有効範囲を「ドメイン配下全て」に設定
			'secure' => true, //httpsのみ
			'httponly' => true // JavaScriptからのアクセスは不可とする
		];

		setcookie('remember_token', $remember_token, $options);

		header('Location: https://' . $_SERVER['HTTP_HOST'] . '/user/');
		exit;
}
?>