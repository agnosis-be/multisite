<?php
// This file: /app/controllers/FileController.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1
require_once(AG_INC_DIR.'/Dir.class.php');

/***
 * File controller
 *
 * @see '/www/bcknd/index.php' <-- caller
 *
 */
class FileController {

    protected Base $f3;
    protected View $tpl;
    protected Site $site;
    protected Dir $dir;
    protected int $folderID;
    protected ?int $fromID;

    protected string $fileDir;
    protected bool $album;

    protected array $msg;
    protected bool $redirect;

    /**
     * Constructor
     *
     */
    function __construct(Base $f3, View $tpl, Site $tblSite, int $folderID = 0, int $fromID = null) {
        assert($tblSite->loaded() == 1);

        $this->f3 = $f3;
        $this->tpl = $tpl;
        $this->site = $tblSite;
        $this->folderID = $folderID;
        $this->fromID = $fromID;

        $this->album = $this->folderID > 0;
        $this->fileDir = $this->album ? sprintf("%s/%d", $this->f3['setup']['AG_ALBUM_DIR'], $this->folderID) : $this->f3['setup']['AG_FILE_DIR'];
        $strFileRoot = sprintf("%s/%s", $this->f3['setup']['AG_WEB_ROOT'], $this->site->Dir);
        $this->dir = new Dir($this->f3, $strFileRoot, $this->fileDir, $this->album);
        $this->msg = array();
        $this->redirect = false;
    }

    /**
     * List action
     *
     */
    function listAll(): string {

        // Get data
        $arrFile = $this->dir->getFiles();

        // Format file list
        $arrRows = array();
        if (!$arrFile) {
            $arrRows[] = '<tr><td style="font-size: 10pt;"><em>No files uploaded yet</em></td></tr>';
        } else {
            foreach ($arrFile as $strFile) {
                $arrPathInfo = pathinfo($strFile);
                $strExt = strtolower($arrPathInfo["extension"]);
                if ($strExt == "pdf") {
                    $strSrc = sprintf("%s/%s", $this->f3['setup']['AG_FILE_DIR'], "File_Pdf.png");
                } elseif ($strExt == "jpg" || $strExt == "jpeg") {
                    $strSrc = sprintf("http://%s/%s/%s", $this->site->URL, $this->fileDir, $strFile);
                } else {
                    $strSrc= sprintf("%s/%s", $this->f3['setup']['AG_FILE_DIR'], "File_Blank.png");
                }
                $this->f3->set("File", $strFile);
                $this->f3->set("Src", $strSrc);
                $arrRows[] = $this->tpl->render("bcknd/fl_tr.tpl");
            }
        }

        // Populate template
        $strTitle = "";
        $strBtnBackLabel = "";
        $strBtnBackURL = "";

        if ($this->album) {
            $strTitle = sprintf("My Album %d", $this->folderID);
            $strBtnBackLabel = "My Albums";
            $strBtnBackURL = "?c=album";
        } else {
            $strTitle = "My Files";
            $strBtnBackLabel = "My Site";
            $strBtnBackURL = "index.php";
        }
        if ($this->fromID > 0) {
            $strBtnBackLabel = "My Page";
            $strBtnBackURL = sprintf("?id=%d&amp;c=page&amp;a=show", $this->fromID);
        }
        $this->f3->set("PageTitle", $strTitle);
        $this->f3->set("BtnBackLabel", $strBtnBackLabel);
        $this->f3->set("BtnBackURL", $strBtnBackURL);
        if (count($this->msg)) {
            sort($this->msg);
            $this->f3->set("Msg", join("<br>", $this->msg));
        } else {
            $this->f3->set("Msg", "");
        }
        $strMsg2 = sprintf(
            "Please note: Only upload files of type <kbd>%s</kbd> with a maximum size ".
            " of %d MB per file and %d MB per upload.",
            join(", ", $this->dir->getAcceptType()),
            $this->f3['setup']['AG_FILE_SIZE_QUOTA']/1000000,
            intval(ini_get("post_max_size"))
        );
        $this->f3->set("Msg2", $strMsg2);
        $this->f3->set("Msg3", "");
        $this->f3->set("BodyOnLoad", "");
        $this->f3->set("MsgOnClick", $this->redirect ? sprintf("location.href='?c=file&amp;a=list&amp;album=%d&amp;from=%d'", $this->folderID, $this->fromID) : "ag_ToggleLock('')");
        $this->f3->set("TopNav", $this->tpl->render("bcknd/topnav.html"));
        $this->f3->set("Files", join("\n", $arrRows));
        $this->f3->set("From", $this->fromID);
        $this->f3->set("Album", $this->folderID);
        $this->f3->set("Content", $this->tpl->render("bcknd/fl.tpl"));
        return $this->tpl->render("bcknd/site.tpl");
    }

    /**
     * Delete action
     *
     */
    function del(string $strFile): bool {
        $boolDeleted = $this->dir->deleteFile($strFile);
        $this->msg = $this->dir->getMsg();
        $this->redirect = true;
        return $boolDeleted;
    }

    /**
     * Add action
     *
     * @param   array   $files    $_FILES
     * @return  Boolean
     */
    function add(array $files): bool {
        $boolAdded = $this->dir->upload($files, $this->fileDir);
        $this->msg = $this->dir->getMsg();
        $this->redirect = true;
        return $boolAdded;
    }
}
?>
