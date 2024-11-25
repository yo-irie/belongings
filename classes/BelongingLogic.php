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
     * カテゴリーidを英語名称から取得
     * @param string
     * @return array|bool $category|false
     */
    public static function getCategoryByName($category_name)
    {
        //SQLの準備
        $sql = 'SELECT * FROM belonging_masters WHERE category_name_en = :category_name_en';
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':category_name_en', $category_name, \PDO::PARAM_STR);
            $stmt->execute();
            $category = $stmt->fetch(PDO::FETCH_ASSOC);//SELECTしたSQLの結果を返す
            return $category;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ユーザーの持ち物リストを登録する
     * @param array $belongingData
     * @return bool $result
     */
    public static function createBelonging($belongingData)
    {
        $sql = 'INSERT INTO user_belongings (user_id, category_id, belonging_name, note, expiry_date, attribute, update_at) VALUES (?, ?, ?, ?, ?, ?, ?)';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $belongingData['user_id'];
        $arr[] = $belongingData['category_id'];
        $arr[] = $belongingData['belonging_name'];
        $arr[] = $belongingData['note'];
        $arr[] = $belongingData['expiry_date'];
        $arr[] = 'bg';
        $arr[] = (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s');

        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    /**
     * ユーザーの持ち物リスト情報を更新する
     * @param array $belongingData | int $belongingid
     * @return bool $result
     */
    public static function updateBelonging($belongingData, $belongingid)
    {
        $sql = 'UPDATE user_belongings SET category_id=?, belonging_name=?, note=?, expiry_date=?, update_at=? WHERE user_belonging_id=?';
        $arr = [];
        $arr[] = $belongingData['category_id'];
        $arr[] = $belongingData['belonging_name'];
        $arr[] = $belongingData['note'];
        $arr[] = $belongingData['expiry_date'];
        $arr[] = (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s');
        $arr[] = $belongingid;
        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    /**
     * ユーザーIDから持ち物リスト情報を取得
     * @param string $userid
     * @return array|bool $user_belonging|false
     */
    public static function getUserBelongingByuserid($userid)
    {
        //SQLの準備
        $sql = 'SELECT * FROM user_belongings WHERE user_id = ? AND attribute = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $userid;
        $arr[] = 'bg';
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);//executeはBool値を返す
            $user_belonging = $stmt->fetchall(PDO::FETCH_ASSOC);//SQLの結果を返す
            return $user_belonging;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ユーザーごとの持ち物リストからuser_belonging_idを指定して持ち物情報を取得
     * @param string $belongingid
     * @return array|bool $user_belonging|false
     */
    public static function getUserBelongingBybelongingid($belongingid)
    {
        //SQLの準備
        $sql = 'SELECT * FROM user_belongings WHERE user_belonging_id = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $belongingid;
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);//executeはBool値を返す
            $user_belonging = $stmt->fetch(PDO::FETCH_ASSOC);//SQLの結果を返す
            return $user_belonging;
        } catch (\Exception $e) {
            return false;
        }
    }

        /**
     * user_belonging_idの値からカテゴリー名称を取得
     * @param string $belongingid
     * @return array|bool $category_name|false
     */
    public static function getCategorynameBybelongingid($belongingid)
    {
        //SQLの準備
        $sql = 'SELECT * FROM user_belongings INNER JOIN belonging_masters ON user_belongings.category_id = belonging_masters.category_id WHERE user_belonging_id = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $belongingid;
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);//executeはBool値を返す
            $category_name = $stmt->fetch(PDO::FETCH_ASSOC);//SQLの結果を返す
            return $category_name;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 持ち物を削除する
     * @param string $belongingid
     * @return bool $result
     */
    public static function deleteBelonging($belongingid)
    {
        $result = false;
        //SQLの準備
        $sql = 'DELETE FROM user_belongings WHERE user_belonging_id = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $belongingid; 
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ユーザーのウィッシュリストを登録する
     * @param array $belongingData
     * @return bool $result
     */
    public static function createWish($belongingData)
    {
        $sql = 'INSERT INTO user_belongings (user_id, category_id, belonging_name, note, release_date, attribute, update_at) VALUES (?, ?, ?, ?, ?, ?, ?)';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $belongingData['user_id'];
        $arr[] = $belongingData['category_id'];
        $arr[] = $belongingData['belonging_name'];
        $arr[] = $belongingData['note'];
        $arr[] = $belongingData['release_date'];
        $arr[] = 'wish';
        $arr[] = (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s');

        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);//executeはBool値を返す
            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    /**
     * ユーザーIDから持ち物リスト情報を取得
     * @param string $userid
     * @return array|bool $user_belonging|false
     */
    public static function getUserWishByuserid($userid)
    {
        //SQLの準備
        $sql = 'SELECT * FROM user_belongings WHERE user_id = ? AND attribute = ?';
        //ユーザーデータを配列に入れる
        $arr = [];
        $arr[] = $userid;
        $arr[] = 'wish';
        //SQLの実行
        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);//executeはBool値を返す
            $user_belonging = $stmt->fetchall(PDO::FETCH_ASSOC);//SQLの結果を返す
            return $user_belonging;
        } catch (\Exception $e) {
            return false;
        }
    }

}