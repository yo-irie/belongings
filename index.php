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
    } else if ($path === '/user/account/account_edit_dis/') {
        require "../belongings/view/account_edit_dis.php";
    } else if ($path === '/user/account/edit/') {
        require "../belongings/mypage/account_edit.php";
    } else if ($path === '/user/account/passedit/') {
        require "../belongings/mypage/account_passedit.php";
    } else if ($path === '/user/account/confirm_delete/') {
        require "../belongings/view/confirm_delete.php";
    } else if ($path === '/user/account/delete/') {
        require "../belongings/mypage/account_delete.php";
    } else if ($path === '/user/bg/') {
        require "../belongings/view/belonging_list.php";
    } else if ($path === '/user/bg/add_page/') {
        require "../belongings/view/belonging_add_page.php";
    } else if ($path === '/user/bg/add/') {
        require "../belongings/mypage/add_belongings.php";
    } else if ($path === '/user/bg/bg_detail/') {
        require "../belongings/view/belonging_detail.php";
    } else if ($path === '/user/bg/update/') {
        require "../belongings/mypage/update_belongings.php";
    } else if ($path === '/user/bg/delete/') {
        require "../belongings/mypage/delete_belongings.php";
    } else if ($path === '/user/wish/') {
        require "../belongings/view/wish_list.php";
    } else if ($path === '/user/wish/add_page/') {
        require "../belongings/view/wish_add_page.php";
    } else if ($path === '/user/wish/add/') {
        require "../belongings/mypage/add_wish.php";
    } else if ($path === '/user/wish/wish_detail/') {
        require "../belongings/view/wish_detail.php";
    } else if ($path === '/user/wish/update/') {
        require "../belongings/mypage/update_wish.php";
    } else if ($path === '/user/wish/delete/') {
        require "../belongings/mypage/delete_wish.php";
    } else {
        header("HTTP/1.1 404 Not Found");
    }