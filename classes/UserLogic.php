<?php
require_once './dbconnect.php';

class UserLogic
{
	/**
	 * ユーザーを登録する
	 * @param array $userData
	 * @return bool $isResult
	 */
	public static function createUser($userData)
	{
		$sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';
		//ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = $userData['username'];
		$arr[] = $userData['email'];
		$arr[] = password_hash($userData['password'], PASSWORD_DEFAULT);

		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ログイン処理
	 * @param string $email
	 * @param string $password
	 * @return bool $isResult
	 */
	public static function login($email, $password)
	{
		$isResult =  false;
		//ユーザーをemailから検索して取得
		$user = self::getUserByEmail($email);
		if (!$user) {//配列が空=データベースに合致するemailが存在しない場合
			$_SESSION['msg'] = 'メールアドレスまたはパスワードが間違っています';//メールとパスワードどちらが間違っているか特定できないようにする
			return $isResult;
		}

		if (password_verify($password, $user['password'])) {
			//ログイン成功処理
			session_regenerate_id(true);//セッション破棄（セッションハイジャック対策）
			$_SESSION['login_user'] = $user;
			$isResult = true;
			return $isResult;
		}

		$_SESSION['msg'] = 'メールアドレスまたはパスワードが間違っています';
		return $isResult;
	}

	/**
	 * emailからユーザーを取得
	 * @param string $email
	 * @return array|bool $user|false
	 */
	public static function getUserByEmail($email)
	{
		//SQLの準備
		$sql = 'SELECT * FROM users WHERE email = ?';
		//ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = $email;
		//SQLの実行
		try {
			$stmt = connect()->prepare($sql);
			$stmt->execute($arr);//executeはBool値を返す
			$user = $stmt->fetch();//SQLの結果を返す
			return $user;
		} catch (\Exception $e) {
			return false;
		}
	}

		/**
	 * remember_token(自動ログイン時のクッキー変数用のトークン)をセット
	 * @param string $remember_token, $email
	 * @return bool $isResult
	 */
	public static function setRememberToken($remember_token, $email)
	{
		$sql = 'UPDATE users SET remember_token = ? WHERE email = ?';
		$arr = [];
		$arr[] = hash('sha256', $remember_token, false);
		$arr[] = $email;
		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

		/**
	 * クッキーのremember_tokenからユーザーを取得
	 * @param void
	 * @return array $user
	 */
	public static function getUserByRemember()
	{
		$isResult = false;
		//SQLの準備
		$sql = 'SELECT * FROM users WHERE remember_token = ? AND status = ?';
		//ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = hash('sha256', $_COOKIE['remember_token'], false);
		$arr[] = 'main';
		//SQLの実行
		try {
			$stmt = connect()->prepare($sql);
			$stmt->execute($arr);//executeはBool値を返す
			$user = $stmt->fetch();//SQLの結果を返す
			return $user;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ログイン済みか判定,セッションが破棄されていたらクッキーにremember_tokenがあるかチェック
	 * @param void
	 * @return bool false
	 */
	public static function checkLogin()
	{
		$isResult = false;
		//セッションにログインユーザーがなかったらfalse
		if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0) {
			return $isResult = true;
		} else if (isset($_COOKIE['remember_token'])) {
			$user = self::getUserByRemember();
			if ($user['remember_token'] === hash('sha256', $_COOKIE['remember_token'], false)) {
				return $isResult = true;
			}
		}
		return false;
	}
	
	/**
	 * ログアウト処理
	 */
	public static function logout()
	{
		// remember_tokenをNULLにする
		$sql = 'UPDATE users SET remember_token = NULL WHERE id = :id';
		$stmt = connect()->prepare($sql);
		$stmt->bindValue(':id', $_SESSION['login_user']['id'], \PDO::PARAM_INT);
		$stmt->execute();
		// remeber_tokenをcookieから削除
		setcookie('remember_token', '', time() - 6000, '/');
		$_SESSION = array();
		// セッションクッキーを削除
		setcookie('PHPSESSID', '', time() - 6000, '/');
		session_destroy();
	}

	/**
	 * パスワード再設定を申請する
	 * @param array $userData
	 * @return bool $isResult
	 */
	public static function requestResetting($userData)
	{
		$sql = 'INSERT INTO  (name, email, password) VALUES (?, ?, ?)';
		//ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = $userData['username'];
		$arr[] = $userData['email'];
		$arr[] = password_hash($userData['password'], PASSWORD_DEFAULT);

		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ログイン済みページでパスワードを更新する
	 * @param string $password
	 * @return bool $isResult
	 */
	public static function updatePassword($password)
	{
		$sql = 'UPDATE users SET password=? WHERE id=?';
		$arr = [];
		//パスワード
		$arr[] = password_hash($password, PASSWORD_DEFAULT);
		//ユーザーID(WHERE句の条件)
		$arr[] = $_SESSION['login_user']['id'];

		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ユーザー情報(名前・Eメール)を更新する
	 * @param array $userData
	 * @return bool $isResult
	 */
	public static function updateProfile($userData)
	{
		$sql = 'UPDATE users SET name=?, email=? WHERE id=?';
		$arr = [];
		$arr[] = $userData['name'];
		$arr[] = $userData['email'];
		//ユーザーID(WHERE句の条件)
		$arr[] = $_SESSION['login_user']['id'];

		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * アカウントを削除する
	 * @param string $email
	 * @return bool $isResult
	 */
	public static function deleteUser($email)
	{
		//SQLの準備
		$sql = 'DELETE FROM users WHERE email = ?';
		//ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = $email;
		//SQLの実行
		try {
			$stmt = connect()->prepare($sql);
			$isResult = $stmt->execute($arr);//executeはBool値を返す
			return $isResult;
		} catch (\Exception $e) {
			return false;
		}
	}
}