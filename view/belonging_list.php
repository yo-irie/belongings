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
    //ログインしているユーザー名表示用
    $login_user = $_SESSION['login_user'];
    $categorylist = BelongingLogic::getCategoryList();
    $user_belonging = BelongingLogic::getUserBelongingByuserid($login_user['id']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>持ち物リスト</title>
</head>
<body>
<h2></h2>
<p><?php echo h($login_user['name']); ?>さんの持ち物リストページです</p>
    <?php for ($i=0; $i < count($categorylist); $i++) : ?>
        <li><?php echo $categorylist[$i]['category_name']; ?></li><!--カテゴリーリスト表示 -->
        <?php for ($j=0; $j < count($user_belonging); $j++) : ?><!--カテゴリーIDが合致したら名前を表示する-->
            <?php if ($i+1 === $user_belonging[$j]['category_id']) :  $belongingid = $user_belonging[$j]['user_belonging_id']; ?>
                <a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/bg/bg_detail?b={$belongingid}"; ?>><?php echo $user_belonging[$j]['belonging_name'] . '<br>';?></a>
            <?php endif; ?>
        <?php endfor; ?>
    <?php endfor; ?>

<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/bg/add_page/"; ?>>持ち物追加<br></a>
<a href=<?php echo "http://". $_SERVER['HTTP_HOST'] . "/user/"; ?>>トップ画面に戻る<br></a>
</form>
</body>
</html>