<?php
require_once './dbconnect.php';
require_once 'UserLogic.php';

class BelongingLogic
{
    /**
     * カテゴリーリストを取得
     * @param void
     * @return array|bool $categorylist|false
     */
    public static function getCategoryList()
    {
        //SQLの準備
        $sql = 'SELECT * FROM belonging_masters';
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute();
            $categorylist = $stmt->fetchall(PDO::FETCH_ASSOC);//SELECTしたSQLの結果を返す
            return $categorylist;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ユーザーの持ち物を登録する
     * @param array $belongingData
     * @return bool $result
     */
    public static function createBelonging($belongingData)
    {
        $sql = 'INSERT INTO user_belongings (user_id, category_id, note, expiry_date, update_at) VALUES (?, ?, ?, ?, ?)';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $_SESSION['login_user']['user_id'];
        $arr[] = $belongingData['category_id'];
        $arr[] = $belongingData['note'];
        $arr[] = $belongingData['expriry_date'];
        $arr[] = (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s');

        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    /**要修正
     * ユーザーの持ち物情報を更新する
     * @param array $belongingData
     * @return bool $result
     
    public static function updateBelonging($belongingData)
    {
        $result = false;
        UserLogic::checkLogin();
        if (!$result) exit('ログインしてください');

        $sql = 'UPDATE users SET category_id=?, note=?, expiry_date=? WHERE user_id = ?';
        //入力データが空なら更新しないために今の登録内容を配列にいれる
        $arr = [];
        if (empty($belongingData['categoryu_id'])) {
            $arr[] = $_SESSION['login_user']['name'];
        } else {
            $arr[] = $userData['username'];
        }
        if (empty($userData['email'])) {
            $arr[] = $_SESSION['login_user']['email'];
        } else {
            $arr[] = $userData['email'];
        }
        if (empty($userData['password'])) {
            $arr[] = password_hash($_SESSION['login_user']['password'], PASSWORD_DEFAULT);
        } else {
            $arr[] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }*/

    /**
     * ？？から持ち物情報を取得
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
     * アカウントを削除する
     */
    public static function deleteUser($email)
    {
        //SQLの準備
        $sql = 'DELETE FROM users WHERE email = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        if (empty($_SESSION['login_user']['email'])){
            exit('ログインしてください');
        } else {
            $arr[] = $_SESSION['login_user']['email'];
        }
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);//executeはBool値を返す
        } catch (\Exception $e) {
            return false;
        }
    }
}