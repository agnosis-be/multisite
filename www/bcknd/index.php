<?php
// This file: /www/bcknd/index.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1

/***
 * CMS backend for website owners
 *
 * Usage:
 *   GET:
 *     index.php?c={$c}&amp;a={$a};id={$id}
 *     where $a (action) in ["show","list"]
 *
 *   POST:
 *     <form method="post">
 *       <input type="hidden" name="c" value="{$c}">
 *       <input type="hidden" name="a" value="{$a}">
 *       <input type="hidden" name="id" value="{$id}">
 *     </form>
 *     where $a in ["update","del","add","logout"]
 *
 *   and where $c (controller) in ["site","auth","page","file","album"]
 *
 */

require_once("../../app/setup.php");
require_once(AG_INC_DIR . "/sssn.php"); // <-- Login is required for this script!
require_once(AG_INC_DIR . "/models/Site.class.php");
require_once(AG_INC_DIR . "/models/Content.class.php");
require_once(AG_INC_DIR . "/controllers/SiteController.class.php");
require_once(AG_INC_DIR . "/controllers/AuthController.class.php");
require_once(AG_INC_DIR . "/controllers/PageController.class.php");
require_once(AG_INC_DIR . "/controllers/FileController.class.php");
require_once(AG_INC_DIR . "/controllers/AlbumController.class.php");

$f3 = Base::instance();
$tpl = new View();
$tblSite = new Site($f3);
$tblSite->load(["ID = ?", $_SESSION["SITE_ID"]]);
$tblContent = new Content($f3);

// controller
$c = trim($_REQUEST["c"] ?? '');
// action
$a = trim($_REQUEST["a"] ?? '');
// id
$id = intval($_REQUEST["id"] ?? 0);


// switch controller
switch ($c) {
    case "site":
        $tblSite->reset();
        $objSiteController = new SiteController($f3, $tpl, $tblSite);
        if (count($_POST)>0) {
            $objSiteController->update($_SESSION["SITE_ID"], $_POST);
        }
        echo $objSiteController->show($_SESSION["SITE_ID"]);
        break;

    case "auth":
        $objAuthController = new AuthController($f3, $tpl, $tblSite);
        if ($a == "logout") {
            if ($objAuthController->logout()) {
                header("Location: login.php");
                exit;
            } else {
                $f3->set("PageTitle", $_SESSION["SITE_URL"]);
                $f3->set("Content", $tpl->render('bcknd/ndx.tpl'));
                $tpl->assign("Msg", "Logout failed");
                echo $tpl->render("site.tpl");
            }
        }
        break;

    case "page":
        $objPageController = new PageController($f3, $tpl, $tblSite, $tblContent);
        if ($a == "add") {
            $objPageController->add();
            echo $objPageController->listAll();
        } elseif ($a == "del") {
            $objPageController->del(intval(key($_POST["del"])));
            echo $objPageController->listAll();
        } elseif ($a == "show") {
            echo $objPageController->show($id);
        } elseif ($a == "update") {
            $objPageController->update($id, $_POST);
            echo $objPageController->show($id);
        } elseif ($a == "list") {
            echo $objPageController->listAll();
        }
        break;

    case "file":
        $intAlbum = intval($_REQUEST["album"] ?? 0);
        $intFrom = intval($_REQUEST["from"] ?? 0);
        $objFileController = new FileController($f3, $tpl, $tblSite, $intAlbum, $intFrom);

        if (count($_FILES)>0) {
            $objFileController->add($_FILES);
        } elseif (isset($_POST["delete"])) {
            $strFileDel = key($_POST["delete"]);
            $objFileController->del($strFileDel);
        }
        echo $objFileController->listAll();
        break;

    case "album":
        $intFrom = intval($_REQUEST["from"] ?? 0);
        $objAlbumController = new AlbumController($f3, $tpl, $tblSite, $tblContent, $intFrom);

        if (isset($_POST["add"])) {
            $objAlbumController->add();
        } elseif (isset($_POST["del"])) {
            $intDel = intval(key($_POST["del"]));
            $objAlbumController->del($intDel);
        }
        echo $objAlbumController->listAll();
        break;

    default:
        $f3->set("PageTitle", $_SESSION["SITE_URL"]);
        $f3->set("BodyOnLoad", "");
        $f3->set("TopNav", "");
        $f3->set("Content", $tpl->render('bcknd/ndx.tpl'));
        echo $tpl->render("bcknd/site.tpl");
        break;
}
?>
