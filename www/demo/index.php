<?php
// This file: /www/demo/index.php' (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Demo website using the Multi-site CMS
 * by setting only 2 configuration values:
 *
 * - SITE_ID <-- ID of record in table tblSite
 * - LANG    <-- default language, uses content from tblContent.Data
 */
define("SITE_ID", 1);
define("LANG", "en");
require_once("../multisite/base.php");
?>
