<?php
// This file: /app/models/Content.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1
require_once('Table.class.php');

/***
 * OR Mapper for database table 'tblContent'.
 * One row in this table represents one page of a website,
 * i.e. page 'About' of www.example.org.
 *
 * Properties are automatically reflected
 *
 * Usage:
 *   $f3 = Base::instance();
 *   $tblContent = new Content($f3);
 *
 * @see    Table.class.php <-- Parent
 * @see    Site.class.php  <-- OR Mapper for 'tblSite'
 */
class Content extends Table {

    // Data  := content for default language
    // Data2 := content for alternative language (if any)
    const dataFields = [
        "Data",
        "Data2"
    ];

    function __construct(Base $f3) {
        parent::__construct($f3->db, $f3['setup']['TBL_CONTENT']);

        $this->beforesave(function($self,$pkeys){
            if (strlen(trim(strval($self->get('Title')))) == 0) {
                $self->set('Title', '(No Title)');
            }
        });
    }

    /**
     * Generate access token to hinder URL guessing of hidden pages
     *
     */
    static function genUrlPasswd(int $len): string {
        return substr(bin2hex(openssl_random_pseudo_bytes($len)), 0, $len);
    }

    /**
     * Get navigation bar of given site
     *
     */
    static function getNavBar(Base $f3, int $intSiteID) {
        $stmt = sprintf(
            "SELECT * FROM %s WHERE SiteID = ? AND NavBarYN = 1",
            $f3['setup']['TBL_CONTENT']
        );
        $arr = $f3->db->exec($stmt, [$intSiteID]);

        if (!$arr) return false;

        // Sort items
        $i = 0;
        $found = false;
        do {
            $a = array_shift($arr);
            foreach ($arr as $k => $a2) {
                if ($a["NavBarPosAfterID"] == $a2["ID"]) {
                    array_splice($arr, $k+1, 0, array($a));
                    $found = true;
                    break;
                }
            }
            if (!$found) $arr[] = $a;
            // Prevent deadloop
            $i++;
            if ($i > 100) break;
        } while ($arr[0]["NavBarPosAfterID"]>0);

        $arrNavBar = array();
        foreach ($arr as $a) {
            $arrNavBar[$a["ID"]] = $a;
        }
        return $arrNavBar;
    }
}
?>
