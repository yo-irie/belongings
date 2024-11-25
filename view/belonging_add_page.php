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
    $csrf_token = setToken();
    $_SESSION['csrf_token'] = $csrf_token;
    $categorylist = BelongingLogic::getCategoryList();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>持ち物追加</title>
</head>
<body>
<h2>持ち物リストを追加</h2>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/bg/add/"; ?> method="POST">

    <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">

    <label for="select_ategory">カテゴリーを選択</label>
    <select name="select_category">
        <?php for ($i=0; $i < count($categorylist); $i++) : ?>
            <option value="<?php echo $categorylist[$i]['category_name_en']; ?>"><?php echo $categorylist[$i]['category_name']; ?></option>
        <?php endfor; ?>
    </select><br>

    <label for="bgname">登録する持ち物を入力</label>
    <input type="text" name="belonging_name" required><br>

    <label for="note">メモを入力</label>
    <input type="text" name="note"><br>

    <label for="expiry">使用期限を入力</label>
    <input type="date" name="expiry_date" min="2024-01-01" max="9999-12-31"><br>

    <input type="submit" name="add" value="追加"> 
</form>

<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/"; ?>>トップに戻る<br></a>
</body>
</html>