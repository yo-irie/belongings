<?php
    session_start();

    $csrf_token = filter_input(INPUT_POST, 'csrf_token');

    //トークンを検証する
    if (empty($csrf_token) || empty($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }

    $email = filter_input(INPUT_POST, 'email');

    require_once '../belongings/dbconnect.php';
    require_once '../belongings/functions.php';
    require_once '../belongings/classes/UserLogic.php';

    $user = UserLogic::getUserByEmail($email);
    //ユーザー登録済みかつ本登録状態ならメールを送信しない(登録済みかどうかはわからないようにする)
    if(!empty($user) && $user['status'] === 'main') {
        exit('メール送信に失敗しました');
    }

    if(!$user) {
        $sql = 'INSERT INTO users(email, register_token, register_token_sent_at) VALUES (:email, :register_token, :register_token_sent_at)';
    } else {
        $sql = 'UPDATE users SET register_token = :register_token, register_token_sent_at = :register_token_sent_at WHERE email = :email';
    }

    //ユーザーが登録済みでなければEメール、登録用トークン、トークン日時をテーブルに入れる
    //本登録トークン（ユーザー識別用やURL添付用にもなる）
    $registerToken = bin2hex(random_bytes(32));
    $_SESSION['register_token'] = $registerToken;

    //メール送信に失敗したらロールバックするためにトランザクションを設置
    try {
        $pdo = connect();//dbconnect.php
        $pdo->beginTransaction();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->bindValue(':register_token', $registerToken, \PDO::PARAM_STR);
        $stmt->bindValue(':register_token_sent_at', (new DateTime('Asia/Tokyo'))->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $stmt->execute();
        //ここからメール送付処理
        //文字化け対策
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        //メールに表示するURL
        $url = "http://" . $_SERVER["HTTP_HOST"] . "/signup_show?token={$registerToken}";

        $subject = "仮登録が完了しました";

        $body = <<<EOD
                会員登録ありがとうございます。
                24時間以内に下記URLから本登録をしてください。
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
        <h2>メール送信しました。</h2>
        <h2>メールから会員登録をしてください。</h2>
        <a href="http://{$_SERVER["HTTP_HOST"]}/">ログイン画面に戻る</a>
    </body>
    </html>
    EOD;

    echo $html;
?>