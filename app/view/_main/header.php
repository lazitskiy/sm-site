<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $this->get("title"); ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="all">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/public/css/style.css">

    <link rel="stylesheet" media="(min-width: 768px)" href="/public/css/min-768px.css">
    <link rel="stylesheet" media="(min-width: 992px)" href="/public/css/min-992px.css">
    <link rel="stylesheet" media="(min-width: 1200px)" href="/public/css/min-1200px.css">

</head>

<body <?php if ($_SERVER['REQUEST_URI'] != '/') { ?> class="super-black" <? } ?>>

<?php
$_ = $this->get('_');
?>

<header class="nagivation">
    <div class="logo">
        <a href="/">
            <img src="/images/logo.png" height="50" alt="">
        </a>
    </div>
    <span class="slogan"><?php echo $_['Slogan'] ?></span>

    <div class="main-nav-links">

        <ul class="nav-links">
            <li><a href="/"><?php echo $_['Main'] ?></a></li>
            <li><a href="/movies"><?php echo $_['Movies'] ?></a></li>
        </ul>
        <ul class="nav-links nav-link-guest">
            <li>
                <a class="login-nav-btn" href="/login"> <?php echo $_['Login'] ?></a> |
                <a class="register-nav-btn" href="/register"><?php echo $_['Register'] ?></a>
            </li>
        </ul>
    </div>

</header>

<div class="main-content">

