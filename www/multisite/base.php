<?php
// This file: /www/multisite/base.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Shared 'index.php' to be included by every website's index.php
 *
 * @requires    SITE_ID            <-- Set in website's index.php
 * @requires    LANG               <-- (dito)
 * @see         ../demo/index.php  <-- sample website (default language)
 */
require_once("../../app/setup.php");
require_once(AG_INC_DIR . "/models/Site.class.php");
require_once(AG_INC_DIR . "/models/Content.class.php");
require_once(AG_INC_DIR . "/controllers/PageController.class.php");

if (!defined("SITE_ID")) die("ERROR: SITE_ID undefined");
if (!defined("LANG")) die("ERROR: LANG undefined");

$f3 = Base::instance();
$tpl = new View();
$tblSite = new Site($f3);
$tblSite->load(['ID = ?', SITE_ID]);
$tblContent = new Content($f3);

//
// Parse URL
//
if (isset($_SERVER["PATH_INFO"])) {
    list($null, $_id, $title) = explode("/", $_SERVER["PATH_INFO"]);
    $_id = (int) $_id;
} else {
    $_id = (int) $_GET["id"];
    $pw = $_GET["pw"];
}

//
// Show page
//
$objPageController = new PageController($f3, $tpl, $tblSite, $tblContent);
echo $objPageController->showPublic($_id, LANG, $title, $pw);
?>
