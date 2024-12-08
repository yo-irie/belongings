<?php
  if (session_status() == PHP_SESSION_NONE) session_start();
  require_once '../public_html/dbconnect.php';
  require_once '../public_html/classes/UserLogic.php';
  $request = filter_input_array(INPUT_POST);

  if (empty($request['csrf_token']) || empty($_SESSION['csrf_token']) || $request['csrf_token'] !== $_SESSION['csrf_token']) {
    exit('不正なリクエストです');
  }
  //エラーメッセージ保持用
  $err = [];
  //現在のパスワードが間違っていた場合
  if (!password_verify($request['password_current'], $_SESSION['login_user']['password'])) {
    $err['current'] = "現在のパスワードが間違っています";
  }
  //バリデーション
  $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,100}$/';
  if (!preg_match($pattern, $request['password'])) {
    $err['reg'] = "パスワードは大小英数字をそれぞれ最低1文字以上含め、8文字以上100文字以内にしてください";
  }
  if ($request['password'] !== $request['password_conf']) {
    $err['match'] = "確認パスワードが間違っています";
  }
  if (count($err) > 0) {
    //エラーがあれば戻す
    $_SESSION['current'] = $err['current'];
    $_SESSION['reg'] = $err['reg'];
    $_SESSION['match'] = $err['match'];
    foreach ($request as $key => $value) {
      $_SESSION["{$key}"] = $value;
    }
    header('Location: https://' . $_SERVER['HTTP_HOST'] . '/user/account/account_edit_dis/');
    exit;
  }

  //エラーがなければユーザーデータを更新
  $isResult = UserLogic::updatePassword($request['password']);
  if (!$isResult) exit('パスワード更新に失敗しました');

  UserLogic::logout();
  echo 'パスワードを更新しました。ログインしなおしてください。';
  echo "<a href='https://" . $_SERVER['HTTP_HOST'] . "'> ログイン画面へ</a>";
?>