<?php
    if (session_status() == PHP_SESSION_NONE) session_start();

    //ログインしていたらマイページにログイン
    require_once '../belongings/classes/UserLogic.php';
    require_once '../belongings/functions.php';
    $result = UserLogic::checkLogin();
    if ($result){
        header("Location: http://" . $_SERVER["HTTP_HOST"] . "/user/");
        return;
    }

    $err = $_SESSION;

    //ログインしていない場合
    $login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
    unset($_SESSION['login_err']);

    $_SESSION = array();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
<h2>ログインフォーム</h2>
    <?php echo $login_err; ?>
            <?php if(isset($err['msg'])) {
                echo "<p>" . $err['msg'] . "</p>";
            } ?>
    <form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/login/"; ?> method="POST">
        <p>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" required>
            <?php if(isset($err['email_empty'])) {
                echo "<p>" . $err['email_empty'] . "</p>";
            } ?>
        </p>
        
        <p>
            <label for="password">パスワード</label>
            <input type="password" name="password" required>
            <?php if(isset($err['password_empty'])) {
                echo "<p>" . $err['password_empty'] . "</p>";
            } ?>
        </p>
        <p>
            <input type="submit" value="ログイン">
        </p>
    </form>
    <a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/resetrequest/"; ?>>パスワードをお忘れの場合<br></a>
    <a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/signuprequest/"; ?>>新規登録はこちら</a>
</body>
</html>