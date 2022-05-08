<?php
// This file: /www/bcknd/login.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Login to CMS backend
 *
 * @see    '/www/bcknd/index.php' <-- Script that requires login
 *
 */

require_once("../../app/setup.php");
require_once(AG_INC_DIR . "/models/Site.class.php");
require_once(AG_INC_DIR . "/controllers/AuthController.class.php");

session_start();

$f3 = Base::instance();
$tpl = new View();
$tblSite = new Site($f3);

$objAuthController = new AuthController($f3, $tpl, $tblSite);

if (count($_POST)>0) {
    $strUser = trim(strip_tags($_POST["Login"]));
    $strPassword = trim(strip_tags($_POST["Passwd"]));
    if ($objAuthController->login($strUser, $strPassword) === true) {
        header("Location: index.php");
        exit;
    }
}

echo $objAuthController->show();
?>
