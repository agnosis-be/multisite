<?php
// This file: /app/setup.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0
require_once(realpath(__DIR__ . '/../thirdparty/composer/vendor/autoload.php'));

/***
 * Global configuration file shared by all websites
 *
 * @requires setup.ini  <-- untracked copy of setup.ini.skel
 * @provides $f3        <-- Fat-Free framework variable
 * @provides $f3->db    <-- database connection
 * @see      https://fatfreeframework.com/3.7/databases
 * @see      https://fatfreeframework.com/3.7/views-and-templates
 */

/////////////////////////////////////////////////
//
// DO NOT EDIT THIS FILE, INSTEAD EDIT setup.ini
//
/////////////////////////////////////////////////

// Read ini file (untracked copy of setup.ini.skel)
$arrMyConf = parse_ini_file("setup.ini");
if (!$arrMyConf) die("ERROR: cannot find setup.ini (untracked copy of setup.ini.skel)");

// Set include dir
define("AG_INC_DIR", $arrMyConf["AG_INC_DIR"]);

// Bootstrap Fat-Free
$f3 = Base::instance();
$f3->set('ENCODING', 'UTF-8');

// Set default escape behaviour when rendering HTML
$f3->set('ESCAPE', FALSE);

// Set debug level (0 := suppress)
$f3->set('DEBUG', intval($arrMyConf["AG_DEBUG_LEVEL"]));

// Set error handler
$f3->set('ONERROR',
    function($f3) {
        printf('ERROR %d: %s', $f3->get('ERROR.code'), $f3->get('ERROR.status'));
        if ($f3->get('DEBUG') > 0) {
            printf("<!-- %s -->", $f3->get('ERROR.text'));
        }
    }
);
// Set db connection
$db = new DB\SQL(
    'mysql:host='.$arrMyConf["AG_DB_HOST"].';port=3306;dbname='.$arrMyConf["AG_DB_NAME"],
    $arrMyConf["AG_DB_USER"],
    $arrMyConf["AG_DB_PASSWD"],
    [PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8mb4;']
);
$f3->set('db', $db);

// Set config values
$f3->set('setup', array(
    'AG_WEB_ROOT' => $arrMyConf['AG_WEB_ROOT'],
    'AG_STATIC_URL' => $arrMyConf['AG_STATIC_URL'],
    'AG_FILE_DIR' => $arrMyConf['AG_FILE_DIR'],
    'AG_FILE_DIR_SIZE_QUOTA' => intval($arrMyConf['AG_FILE_DIR_SIZE_QUOTA']),
    'AG_FILE_SIZE_QUOTA' => intval($arrMyConf['AG_FILE_SIZE_QUOTA']),
    'AG_ALBUM_DIR' => $arrMyConf['AG_ALBUM_DIR'],
    'AG_INC_DIR' => $arrMyConf['AG_INC_DIR'],
    'AG_LANG_OPTS' => $arrMyConf['AG_LANG_OPTS'],
    'TBL_CONTENT' => 'tblContent',
    'TBL_SITE' => 'tblSite'
));
$f3->set('UI', $f3['setup']['AG_INC_DIR'].'/views/');
unset($arrMyConf);
?>
