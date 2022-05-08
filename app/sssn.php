<?php
// This file: /app/sssn.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Session script to be included by all CMS backend files that require login
 *
 */
session_start();
If (!isset($_SESSION["SITE_ID"])) {
    header("Location: login.php");
    exit;
}
?>
