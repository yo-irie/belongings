<?php
    if (session_id() === '') session_start();

    require_once '../belongings/classes/UserLogic.php';
    require_once '../belongings/classes/BelongingLogic.php';
    //ログインしているか判定し、していなければログイン画面へ
    $result = UserLogic::checkLogin();
    if(!$result) {
        $_SESSION['login_err'] = 'ログインしてください';
        header("Location: http://" . $_SERVER["HTTP_HOST"]);
        return;
    }
    //データ受け取り
    $request = filter_input_array(INPUT_POST);
    //CSRFトークンが不正ならば処理を中断する
    if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }
    //カテゴリーID取得用
    $category = BelongingLogic::getCategoryByName($request['select_category']);
    //UPDATE用データの配列作成
    $belongingData['category_id'] = $category['category_id'];
    $belongingData['belonging_name'] = $request['belonging_name'];
    $belongingData['note'] = $request['note'];
    $belongingData['expiry_date'] = $request['expiry_date'];
    //エラーがなければユーザーデータを更新
    $result = BelongingLogic::updateBelonging($belongingData, $request['belongingid']);
    if (!$result) {
        exit('持ち物更新に失敗しました');
    }

    header('Location: http://'. $_SERVER['HTTP_HOST'] .'/user/bg/');

?>