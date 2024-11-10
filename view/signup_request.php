<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    require_once '../belongings/functions.php';
    require_once '../belongings/classes/UserLogic.php';

    //ログインしていたらマイページにリダイレクト
    $result = UserLogic::checkLogin();
    if ($result){
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/user/');
    }

    $csrf_token = setToken();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>仮会員登録</title>
</head>
<body>
<h2>仮会員登録</h2>
    <form action=<?php echo "http://" . h($_SERVER['HTTP_HOST']) . "/signup_tmp/"; ?>  method="POST">
        <p>
            <label for="email">登録するメールアドレスを入力してください</label>
            <input type="email" name="email">
        </p>
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
        <p>
            <input type="submit" value="送付">
        </p>
        
    </form>
    <a href=<?php echo "http://" . $_SERVER['HTTP_HOST']; ?>>ログイン画面へ</a>
</body>
</html>