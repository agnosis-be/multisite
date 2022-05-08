<?php
// This file: /app/controllers/AlbumController.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0
require_once(AG_INC_DIR.'/Dir.class.php');

/***
 * Album controller
 *
 * An album is a directory with user images,
 * that can be published on a page of a given website.
 *
 * Whether a page is referring to an album or not is denoted by
 *     tblContent.AlbumYN
 *     tblContent.AlbumDir
 *
 * @see '/www/bcknd/index.php' <-- caller
 *
 */
class AlbumController {

    protected Base $f3;
    protected View $tpl;
    protected Site $site;
    protected Content $content;
    protected Dir $dir;

    protected ?int $fromID;
    protected string $albumDir;
    protected string $msg;
    protected bool $redirect;

    /**
     * Constructor
     *
     */
    function __construct(Base $f3, View $tpl, Site $tblSite, Content $tblContent, int $fromID = null) {
        assert($tblSite->loaded() == 1);

        $this->f3 = $f3;
        $this->tpl = $tpl;
        $this->site = $tblSite;
        $this->content = $tblContent;
        $this->fromID = $fromID;

        $this->msg = "";
        $this->redirect = false;

        $this->albumDir = sprintf("%s/%s/%s", $this->f3['setup']['AG_WEB_ROOT'], $this->site->Dir, $this->f3['setup']['AG_ALBUM_DIR']);
        $this->dir = new Dir($this->f3, $this->albumDir);
    }

    /**
     * List action
     *
     */
    function listAll(): string {
        $arrAlbum = $this->dir->getSubDirs();

        // Format album list
        $arrRows = array();
        if (!$arrAlbum) {
            $arrRows[] = '<tr><td style="font-size: 10pt;"><em>No albums created yet</em></td></tr>';
        } else {
            // Get pages that use an album
            $stmt = sprintf(
                "SiteID = ? AND AlbumYN = 1 AND AlbumDir IN (%s)",
                Table::toParams($arrAlbum)
            );
            $res = $this->content->find([$stmt, $this->site->ID, ...$arrAlbum]);
            $arrPages = array();
            if (count($res)>0) {
                foreach ($res as $row) {
                   $arrPages[$row["AlbumDir"]] = $row;
                }
            }
            foreach ($arrAlbum as $intID) {
                $this->f3->set("ID", $intID);
                if (isset($arrPages[$intID])) {
                    $strTitle = $arrPages[$intID]["Title"];
                } else {
                    $strTitle = "<em>Not yet published</em>";
                }
                $this->f3->set("Title", $strTitle);
                $arrRows[] = $this->tpl->render("bcknd/lbm_tr.tpl");
            }
        }

        // Populate template
        if ($this->fromID > 0) {
            $strBtnBackURL = sprintf("?c=page&amp;a=edit&amp;id=%d", $this->fromID);
            $strBtnBackLabel = "My Page";
        } else {
            $strBtnBackURL = "index.php";
            $strBtnBackLabel = "My Site";
        }
        $this->f3->set("From", $this->fromID);
        $this->f3->set("BtnBackURL", $strBtnBackURL);
        $this->f3->set("BtnBackLabel", $strBtnBackLabel);
        $this->f3->set("PageTitle", "My Albums");
        $this->f3->set("Msg", $this->msg);
        $this->f3->set("MsgOnClick", $this->redirect ? "location.href='?c=album'" : "ag_ToggleLock('')");
        $this->f3->set("Albums", join("\n", $arrRows));
        $this->f3->set("TopNav", $this->tpl->render("bcknd/topnav.html"));
        $this->f3->set("Content", $this->tpl->render("bcknd/lbm.tpl"));
        return $this->tpl->render("bcknd/site.tpl");
    }

    /**
     * Delete action
     *
     */
    function del(int $intDir): bool {
        // Delete album
        $strDel = sprintf("%s/%d", $this->albumDir, $intDir);
        $boolDeleted = @rmdir($strDel);
        if ($boolDeleted) {
            $stmt = sprintf(
                "UPDATE %s SET AlbumYN = 0, AlbumDir = Null WHERE AlbumDir = ? AND SiteID = ?",
                $this->f3['setup']['TBL_CONTENT']
            );
            $this->f3->db->exec($stmt, [$intDir, $this->site->ID]);
            $this->msg = sprintf("OK: Album %d has been deleted", $intDir);
        } else {
            $this->msg = sprintf("ERROR: Could not delete album %d. Maybe album is not empty?", $intDir);
        }
        $this->redirect = True;
        return $boolDeleted;
    }

    /**
     * Add action
     *
     */
    function add(): bool {
        // Add album
        $arrAlbum = $this->dir->getSubDirs();
        if (!$arrAlbum) {
            $intAdd = 1;
        } else {
            $intAdd = intval(max($arrAlbum)) + 1;
        }
        $strAdd = $this->albumDir . "/" . $intAdd;
        $boolAdded = mkdir($strAdd);
        if ($boolAdded) {
            $this->msg = sprintf("OK: Album %d has been added", $intAdd);
        } else {
            $this->msg = sprintf("ERROR: Album could not be added", $intAdd);
        }
        $this->redirect = True;
        return $boolAdded;
    }
}
?>
