<?php
    if (session_status() == PHP_SESSION_NONE) session_start();

    $csrf_token = filter_input(INPUT_POST, 'csrf_token');

    //トークンを検証する
    if (empty($csrf_token) || empty($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }

    $email = filter_input(INPUT_POST, 'email');

    require_once '../belongings/dbconnect.php';
    require_once '../belongings/functions.php';
    require_once '../belongings/classes/UserLogic.php';

    $passwordResetToken = bin2hex(random_bytes(32));
    $_SESSION['reset_token'] = $passwordResetToken;

    $user = UserLogic::getUserByEmail($email);
    //ユーザーが登録されていなければexit(ユーザー登録されていないメッセージは出さない)
    if(empty($user)) {
        exit('メール送信に失敗しました');
    }

    $pdo = connect();
    $sql = 'SELECT * FROM password_resets WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $passwordResetUser = $stmt->fetch();

    if (!$passwordResetUser) {
        $sql = 'INSERT INTO password_resets (email, token, token_created_at) VALUES (:email, :token, :token_created_at)';
    } else {
        $sql = 'UPDATE password_resets SET email = :email, token = :token, token_created_at = :token_created_at';
    }
    //メール送信に失敗したらロールバックするためにトランザクションを設置
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->bindValue(':token', $passwordResetToken, \PDO::PARAM_STR);
        $stmt->bindValue(':token_created_at', (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $stmt->execute();

        //ここからメール送付処理
        //文字化け対策
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        //メールに表示するURL
        $url = "http://" . $_SERVER["HTTP_HOST"] . "/reset_show?token={$passwordResetToken}";

        $subject = "パスワード再登録のメールを送付します";

        $body = <<<EOD
                24時間以内に下記URLからパスワード再登録をしてください。
                {$url}
                EOD;
        $headers = 'From: testmail@gmail.com' . "\r\n";

        $isSent = mail($email, $subject, $body, $headers);//失敗したらfalse
        if (!$isSent) throw new \Exception('メール送信に失敗しました');

        //失敗しなかったら仮登録を確定する
        $pdo->commit();

    } catch (\Exception $e) {
        $pdo->rollBack();
        exit($e->getMessage());
    }

    //送信済み画面を表示する
    $html = <<<EOD
    <!DOCTYPE html>
    <html lang="ja">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール送信完了</title>
    </head>
    <body>
        <h2>メールを送信しました。</h2>
        <h2>メールからパスワード再設定をしてください。</h2>
        <a href="http://{$_SERVER["HTTP_HOST"]}/">ログイン画面に戻る</a>
    </body>
    </html>
    EOD;

    echo $html;
?>