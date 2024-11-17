<?php
    require_once './functions.php';
    $path = cleanHostString($_SERVER['REQUEST_URI']);

    if ($path === '/') {
        require "../belongings/login/login_form.php";
    } else if ($path === '/signuprequest/') {
        require "../belongings/view/signup_request.php";
    } else if ($path === '/signup_tmp/') {
        require "../belongings/signup/tmp_signup.php";
    } else if ($path === '/signup_show/') {
        require "../belongings/signup/show_signup.php";
    } else if ($path === '/signup_form/') {
        require "../belongings/view/signup_form.php";
    } else if ($path === '/signup/') {
        require "../belongings/signup/signup.php";
    } else if ($path === '/resetrequest/') {
        require "../belongings/view/reset_request.php";
    } else if ($path === '/reset_tmp/') {
        require "../belongings/passreset/tmp_reset.php";
    } else if ($path === '/reset_show/') {
        require "../belongings/passreset/show_reset.php";
    } else if ($path === '/reset_form/') {
        require "../belongings/view/reset_form.php";
    } else if ($path === '/reset/') {
        require "../belongings/passreset/reset.php";
    } else if ($path === '/login/') {
        require "../belongings/login/login.php";
    } else if ($path === '/user/') {
        require "../belongings/view/mypage.php";
    } else if ($path === '/user/logout/') {
        require "../belongings/mypage/logout.php";
    } else if ($path === '/user/logout_comp/') {
        require "../belongings/view/logout_comp.php";
    } else if ($path === '/user/account/') {
        require "../belongings/view/account_manage.php";
    } else {
        header("HTTP/1.1 404 Not Found");
    }