<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    require_once '../belongings/functions.php';
    require_once '../belongings/classes/UserLogic.php';
    //ログインしているか判定し、していなければログイン画面へ
    $result = UserLogic::checkLogin();

    if(!$result) {
        $_SESSION['login_err'] = 'ログインしてください';
        header("Location: http://" . $_SERVER["HTTP_HOST"]);
        return;
    }
    //ログインしているユーザー名表示用
    $login_user = $_SESSION['login_user'];
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
</head>
<body>
<h2></h2>
<p><?php echo h($login_user['name']); ?>さん</p>
<p>メールアドレス：<?php echo h($login_user['email']); ?></p>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/logout/"; ?> method="POST">
<input type="submit" name="logout" value="ログアウト"> 
</form>

</body>
</html>