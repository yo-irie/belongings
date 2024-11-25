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
    //CSRF対策トークン生成
    $csrf_token = setToken();
    $_SESSION['csrf_token'] = $csrf_token;
    //ログインしているユーザー名表示用
    $login_user = $_SESSION['login_user'];
    //パスワードチェックした結果エラーがあった場合
    if(isset($_SESSION['current'])) $err['current'] = $_SESSION['current'];
    if(isset($_SESSION['reg'])) $err['reg'] = $_SESSION['reg'];
    if(isset($_SESSION['match'])) $err['match'] = $_SESSION['match'];
    unset($_SESSION['current']);
    unset($_SESSION['reg']);
    unset($_SESSION['match']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント編集</title>
</head>
<body>
<h2></h2>
<p><?php echo h($login_user['name']); ?>さんのアカウントの編集ページです</p>

<p>名前・Eメール変更</p>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/account/edit/"; ?> method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
    <input type="hidden" name="login_name" value="<?php echo h($login_user['name']);?>">
    <p>
        <label for="name">名前を変更</label>
        <input type="text" name="name" value=<?php echo h($login_user['name']); ?> required>
    </p>
    <p>
        <label for="email">Eメールを変更</label>
        <input type="text" name="email" value=<?php echo h($login_user['email']); ?> required>
    </p>
    <input type="submit" name="edit" value="編集"> 
</form>

<p>パスワード変更</p>
<div>以下のフォームに入力して「パスワード変更」をクリックしてください</div>
<form action=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/account/passedit/"; ?> method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']);?>">
    <p>
        <label for="password_current">現在のパスワード</label>
        <input type="password" id="password_current" name="password_current" required>
            <?php if(isset($err['current'])) {
                echo "<p>" . $err['current'] . "</p>";
            } ?>
    </p>
    <p>
        <label for="password">変更するパスワード</label>
        <input type="password" id="password" name="password" required>
            <?php if(isset($err['reg'])) {
                echo "<p>" . $err['reg'] . "</p>";
            } ?>
    </p>
    <p>
        <label for="password_conf">パスワード確認</label>
            <input type="password" id="password_conf" name="password_conf" required>
                <?php if(isset($err['match'])) {
                    echo "<p>" . $err['match'] . "</p>";
                } ?>
    </p>
    <p><input type="submit" value="パスワード変更"></p>
</form>

<a href=<?php echo "http://". h($_SERVER['HTTP_HOST']) . "/user/account/"; ?>>アカウント管理ページに戻る<br></a>
</body>
</html>