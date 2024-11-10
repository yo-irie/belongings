<?php
    session_start();//セッションスタートしてからファイルを読み込む
    require_once '../belongings/dbconnect.php';
    require_once '../belongings/functions.php';
    $pdo = connect();
    //本登録用のトークン(URLのクエリから取得)
    $registerToken = filter_input(INPUT_GET, 'token');

    $sql = 'SELECT * FROM users WHERE register_token = :register_token AND status = :status';

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':register_token', $registerToken, PDO::PARAM_STR);
        $stmt->bindValue(':status', 'tmp', PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        if (!$user) exit('無効なURLです');
        if (empty($_SESSION['register_token'])) $_SESSION['register_token'] = $registerToken;
    } catch (PDOException $e) {
        echo '接続に失敗 :' . $e->getMessage();
    }

    //トークンの有効期限 24時間
    $tokenValid = (new \DateTime('Asia/Tokyo'))->modify("-24 hour")->format("Y-m-d H:i:s");
    //仮登録が24時間以上前なら有効期限切れとする
    if ($user['register_token_sent_at'] < $tokenValid) exit('有効期限切れです');

    //本登録用のトークンが空なら再生成
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));;
    }

    //エラーがなければ本登録画面を表示
    header('Location: http://'. $_SERVER['HTTP_HOST'] .'/signup_form/');

?>