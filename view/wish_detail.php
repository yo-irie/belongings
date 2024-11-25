<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    require_once '../belongings/functions.php';
    require_once '../belongings/classes/UserLogic.php';
    require_once '../belongings/classes/BelongingLogic.php';
    //ログインしているか判定し、していなければログイン画面へ
    $result = UserLogic::checkLogin();
    if(!$result) {
        $_SESSION['login_err'] = 'ログインしてください';
        header("Location: http://" . $_SERVER["HTTP_HOST"]);
        return;
    }
    //URLクエリからデータ取得(b=持ち物ID)
    $request = intval(filter_input(INPUT_GET, 'b'));

    $login_user = $_SESSION['login_user'];
    $user_wish = BelongingLogic::getUserWishByuserid($login_user['id']);
    $categorylist = BelongingLogic::getCategoryList();
    //user_belongin_id=$requestなのでターゲットの持ち物情報を特定する
    $select_by_belongingid = BelongingLogic::getUserBelongingBybelongingid($request);
    //URLを直接された場合の対策、持ち物を追加したユーザーとログインしたユーザーが異なる場合は終了する
    if ($select_by_belongingid['user_id'] !== $login_user['id']) {
        exit('不正なリクエストです');
    }
    //date型データが'0000-00-00'なら空欄にする
    if ($select_by_belongingid['release_date'] == '0000-00-00') $select_by_belongingid['release_date'] = "";
    //CSRFトークンが空であればセッション変数に代入する
    if (empty($_SESSION['csrf_token'])) {
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;
    }
    //カテゴリー名称表示用
    $category_name = BelongingLogic::getCategorynameBybelongingid($request);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>欲しいもの詳細</title>
</head>
<body>
<h2><?php echo h($category_name['category_name']); ?></h2>
<p>名称：<?php echo h($select_by_belongingid['belonging_name']); ?></p>
<p>メモ：<?php echo h($select_by_belongingid['note']); ?></p>
<p>リリース日：<?php echo h($select_by_belongingid['release_date']); ?></p>

<!--編集処理-->
<p>データを更新する</p>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/wish/update/"; ?> method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
    <!--更新する持ち物IDを送信-->
    <input type="hidden" name="belongingid" value="<?php echo h($request); ?>">

    <label for="select_ategory">カテゴリーを選択</label>
    <select name="select_category">
        <?php for ($i=0; $i < count($categorylist); $i++) : ?>
            <option value="<?php echo h($categorylist[$i]['category_name_en']); ?>"><?php echo h($categorylist[$i]['category_name']); ?></option>
        <?php endfor; ?>
    </select><br>

    <label for="bgname">ウィッシュリストの名称を更新</label>
    <input type="text" name="belonging_name" value=<?php echo h($select_by_belongingid['belonging_name']); ?> required><br>

    <label for="note">メモを更新</label>
    <input type="text" name="note" value=<?php echo h($select_by_belongingid['note']); ?>><br>

    <label for="expiry">リリース日を更新</label>
    <input type="date" name="release_date" min="2024-01-01" max="9999-12-31"><br>

    <input type="submit" name="update" value="更新する"> 
</form>

<!--削除処理-->
<p>データを削除する</p>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/wish/delete/"; ?> method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
    <!--削除する持ち物IDを送信-->
    <input type="hidden" name="belongingid" value="<?php echo h($request); ?>">
    <input type="submit" name="delete" value="削除する"> 
</form>
<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/wish/"; ?>>ウィッシュリストへ<br></a>
</body>
</html>