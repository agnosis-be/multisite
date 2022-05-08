<?php
// This file: /app/models/Site.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0
require_once('Table.class.php');

/***
 * OR Mapper for database table 'tblSite'.
 * One row in this table represents one website,
 * i.e. www.example.com, www.example.org, etc.
 *
 * Properties are automatically reflected
 *
 * Usage:
 *   $f3 = Base::instance();
 *   $tblSite = new Site($f3);
 *
 * @see    Table.class.php   <-- Parent
 * @see    Content.class.php <-- OR Mapper for 'tblContent'
 */
class Site extends Table {
    function __construct(Base $f3) {
        parent::__construct($f3->db, $f3['setup']['TBL_SITE']);
    }

    /***
     * Create salted hash for given password
     *
     * @param   string   $passwd
     * @return  string   hash (consisting of algo, salt, and hash)
     */
    static function hashPasswd(string $passwd): string {
        return password_hash($passwd, PASSWORD_DEFAULT);
    }
}
?>
