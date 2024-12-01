<?php
require_once './dbconnect.php';

class UserLogic
{
	/**
	 * ユーザーを登録する
	 * @param array $userData
	 * @return bool $result
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
			$result = $stmt->execute($arr);//executeはBool値を返す
			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ログイン処理
	 * @param string $email
	 * @param string $password
	 * @return bool $result
	 */
	public static function login($email, $password)
	{
		$result =  false;
		//ユーザーをemailから検索して取得
		$user = self::getUserByEmail($email);
		if (!$user) {//配列が空=データベースに合致するemailが存在しない場合
			$_SESSION['msg'] = 'メールアドレスまたはパスワードが間違っています';//メールとパスワードどちらが間違っているか特定できないようにする
			return $result;
		}

		if (password_verify($password, $user['password'])) {
			//ログイン成功処理
			session_regenerate_id(true);//セッション破棄（セッションハイジャック対策）
			$_SESSION['login_user'] = $user;
			$result = true;
			return $result;
		}

		$_SESSION['msg'] = 'メールアドレスまたはパスワードが間違っています';
		return $result;
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
	 * ログイン済みか判定
	 * @param void
	 * @return bool false
	 */
	public static function checkLogin()
	{
		$result = false;
		//セッションにログインユーザーがなかったらfalse
		if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0) {
			return $result = true;
		}
		return $result;
	}
	/**
	 * ログアウト処理
	 */
	public static function logout()
	{
		$_SESSION = array();
		session_destroy();
	}

	/**
	 * パスワード再設定を申請する
	 * @param array $userData
	 * @return bool $result
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
			$result = $stmt->execute($arr);//executeはBool値を返す
			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";;
		}
	}

	/**
	 * ログイン済みページでパスワードを更新する
	 * @param string $password
	 * @return bool $result
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
			$result = $stmt->execute($arr);//executeはBool値を返す
			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * ユーザー情報(名前・Eメール)を更新する
	 * @param array $userData
	 * @return bool $result
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
			$result = $stmt->execute($arr);//executeはBool値を返す
			return $result;
		} catch (\Exception $e) {
			echo $e->getMessage() . "<br>";
		}
	}

	/**
	 * アカウントを削除する
	 * @param string $email
	 * @return bool $result
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
			$result = $stmt->execute($arr);//executeはBool値を返す
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}
}