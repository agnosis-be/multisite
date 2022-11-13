<?php
// This file: /app/controllers/PageController.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1
require_once(AG_INC_DIR.'/Dir.class.php');
require_once(AG_INC_DIR.'/HTML.class.php');

/***
 * Page controller
 *
 * A page is what is rendered in a user's browser
 * either in a frontend (PageController::showPublic)
 * or in a backend version.
 *
 * A page always uses both models:
 * - Site    <-- represents the website (e.g. www.example.org)
 * - Content <-- represents a certain page of this website (e.g. 'About')
 *
 * @see '/www/bcknd/index.php'   <-- frontend caller
 * @see '/www/multisite/base.php <-- backend caller
 * @see https://fatfreeframework.com/3.8/views-and-templates
 *
 */
class PageController {

    protected Base $f3;
    protected View $tpl;
    protected Site $site;
    protected Content $content;

    protected string $msg;
    protected bool $redirect;

    /**
     * Constructor
     *
     */
    function __construct(Base $f3, View $tpl, Site $tblSite, Content $tblContent) {
        assert($tblSite->loaded() == 1);

        $this->f3 = $f3;
        $this->tpl = $tpl;
        $this->site = $tblSite;
        $this->content = $tblContent;

        $this->msg = "";
        $this->redirect = false;
    }

    /**
     * List action
     *
     */
    function listAll(): string {
        // Get pages
        $arrContent = $this->content->find(["SiteID = ?", $this->site->ID]);

        // Populate template
        $arrRows = array();
        foreach ($arrContent as $r) {
            $strData = substr(strip_tags(strval($r["Data"])), 0, 100);
            $strData = str_replace("&nbsp;", " ", $strData);
            $this->f3->set("Data", $strData . "...");
            $this->f3->set("Title", $r["Title"]);
            $this->f3->set("ID", $r["ID"]);
            $arrRows[] = $this->tpl->render('bcknd/pg_tr.tpl');
        }
        $this->f3->set("Rows", join("\n", $arrRows));
        $this->f3->set("PageTitle", "My Pages");
        $this->f3->set("Msg", $this->msg);
        $this->f3->set("BodyOnLoad", "");
        $this->f3->set("TopNav", $this->tpl->render("bcknd/topnav.html"));
        $this->f3->set("MsgOnClick", "location.href='index.php?c=page&amp;a=list'");
        $this->f3->set("Content", $this->tpl->render("bcknd/pg.tpl"));
        return $this->tpl->render("bcknd/site.tpl");
    }

    /**
     * Add action
     *
     */
    function add(): bool {
        $this->content->reset();
        $this->content->SiteID = $this->site->ID;
        $this->content->insert();
        if ($this->content->ID > 0) {
            // Success
            $this->msg = sprintf("OK: Page \'%s\' has been added", $this->content->Title);
            $this->msg.= sprintf(" <a href=\'index.php?id=%d&amp;c=page&amp;a=show\'>Edit page</a>", $this->content->ID);
            return true;
        } else {
            // Error
            $this->msg = 'ERROR: Could not create new page';
            return false;
        }
    }

    /**
     * Delete action
     *
     */
    function del(int $intID): bool {
        if ($intID != $this->site->HomeID) {
            $this->content->load(['ID = ? AND SiteID = ?', $intID, $this->site->ID]);
            $erased = $this->content->erase();
            if ($erased) {
                $this->msg = "OK: Page has been deleted";
            } else {
                $this->msg = "ERROR: Could not delete page";
            }
            return $erased;
        } else {
            $this->msg = "ERROR: You cannot delete your start page";
            return false;
        }
    }

    /**
     * Show action for backend
     *
     */
    function show(int $id): string {
        // Get page
        $this->content->load(["ID = ?", $id]);
        $arrField = $this->content->cast();

        // Get file and album list
        $objFileDir = new Dir($this->f3, sprintf("%s/%s", $this->f3['setup']['AG_WEB_ROOT'], $this->site->Dir), $this->f3['setup']['AG_FILE_DIR']);
        $objAlbumDir = new Dir($this->f3, sprintf("%s/%s", $this->f3['setup']['AG_WEB_ROOT'], $this->site->Dir), $this->f3['setup']['AG_ALBUM_DIR']);
        $arrFile = $objFileDir->getFiles();
        $arrAlbum = $objAlbumDir->getSubDirs();

        // Get navbar
        $arrNavBar = array();
        $_arrNavBar = Content::getNavBar($this->f3, $this->site->ID);
        foreach ($_arrNavBar as $k => $v) {
            $arrNavBar[$k] = $v["Title"];
        }
        unset($_arrNavBar);
        unset($arrNavBar[$id]);

        // Get link to preview
        $strWebURL = sprintf("http://%s/index.php?id=%d", $this->site->URL, $id);
        if ($arrField["UrlPasswdYN"]) {
            $strWebURL .= "&amp;pw=" . $arrField["UrlPasswd"];
        }

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
                    $strSrc = sprintf("http://%s/%s/%s", $this->site->URL, $this->f3['setup']['AG_FILE_DIR'], $strFile);
                } else {
                    $strSrc= sprintf("%s/%s", $this->f3['setup']['AG_FILE_DIR'], "File_Blank.png");
                }
                $this->f3->set("File", $strFile);
                $this->f3->set("Src", $strSrc);
                $arrRows[] = $this->tpl->render("bcknd/dt_tr.tpl");
            }
        }

        // Populate template
        $this->f3->mset($arrField);
        $this->f3->set("id", $id);
        $this->f3->set("WebURL", $strWebURL);
        $this->f3->set("Msg", $this->msg);
        $this->f3->set("Lang", $this->f3['setup']['AG_LANG_OPTS'][$this->site->Lang]);
        $this->f3->set("Lang2", $this->f3['setup']['AG_LANG_OPTS'][$this->site->Lang2] ?? "");
        $this->f3->set("EditorLang2", strlen(strval($this->site->Lang2)) ? $this->tpl->render("bcknd/dt_lang2.tpl") : "");
        $this->f3->set("BodyOnLoad", "ag_ToggleControl(arrCtrl, document.forms[1]);");
        $this->f3->set("PageTitle", "Edit My Page");
        $this->f3->set("Files", join("\n", $arrRows));
        $this->f3->set("MsgOnClick", $this->redirect ? sprintf("location.href='index.php?id=%d&amp;c=page&amp;a=show'", $id) : "ag_ToggleLock('')");
        $this->f3->set("TopNav", $this->tpl->render("bcknd/topnav.html"));
        $this->f3->set("AlbumDirOptions", HTML::toOptions($arrAlbum, $arrField["AlbumDir"]));
        $this->f3->set("NavBarPosAfterIDOptions", HTML::toOptions($arrNavBar, $arrField["NavBarPosAfterID"]));
        $this->f3->set("AlbumYN_checked", $arrField['AlbumYN'] == 1 ? 'checked' : '');
        $this->f3->set("NavBarYN_checked", $arrField['NavBarYN'] == 1 ? 'checked' : '');
        $this->f3->set("UrlPasswdYN_checked", $arrField['UrlPasswdYN'] == 1 ? 'checked' : '');
        $this->f3->set("Content", $this->tpl->render("bcknd/dt.tpl"));
        return $this->tpl->render("bcknd/site.tpl");
    }

    /**
     * Show action for frontend
     *
     */
    function showPublic(int $id, string $lang, string $title = null, string $urlPasswd = null): string {
        $this->site->load(['ID = ?', $this->site->ID]);
        $arrSite = $this->site->cast();

        if (!$arrSite) {
            $this->f3->error(500, "ERROR: no record for site");
        }

        $strTemplate = substr($arrSite["Template"], 0, -4);
        if (!strlen($strTemplate)) {
            $this->f3->error(500, "ERROR: site record has no template");
        }

        $strLangAltDescr = "";
        $strLangAlt = "";
        $strIndexPageAlt = "";

        if ($lang == $arrSite["Lang"]) {
            // Requested language is default language
            $fldTitle = "Title";
            $fldBgTxt = "BgTxt";
            $fldData = "Data";
            $indexPage = "index.php";
            $strDescr = $arrSite["Descr"];
            if (isset($arrSite["Lang2"])) {
                $strIndexPageAlt = "index2.php";
                $strLangAlt = $arrSite["Lang2"];
                $strLangAltDescr = $this->f3['setup']['AG_LANG_OPTS'][$strLangAlt];
            }
        } elseif ($lang == $arrSite["Lang2"]) {
            // Requested language is alternative language
            $fldTitle = "Title2";
            $fldBgTxt = "BgTxt2";
            $fldData = "Data2";
            $indexPage = "index2.php";
            $strDescr = $arrSite["Descr2"];
            $strIndexPageAlt = "index.php";
            $strLangAlt = $arrSite["Lang"];
            $strLangAltDescr = $this->f3['setup']['AG_LANG_OPTS'][$strLangAlt];
        } else {
            $this->f3->error(500, 'ERROR: LANG does not match any record');
        }

        //
        // Get page content
        //
        $this->content->load(['SiteID = ? AND ID = ?', $this->site->ID, $id]);
        if ($this->content->loaded() == 1) {
            $arrContent = $this->content->cast();
        } else {
            $arrContent = [];
        }

        $boolRedirect = false;

        if (!$arrContent || ($arrContent["UrlPasswdYN"] == 1 && $urlPasswd <> $arrContent["UrlPasswd"])) {
            // Page does not exist OR page requires password AND given password is wrong
            $boolRedirect = True;
            $this->content->reset();
            $this->content->load(["SiteID = ? AND ID = ?", $this->site->ID, $this->site->HomeID]);
            if ($this->content->loaded() == 1) {
                $arrContent = $this->content->cast();
            }
        } elseif (!$urlPasswd && substr(urldecode(strtolower(strval($title))), 0, -4) != strtolower(strval($arrContent[$fldTitle]))) {
            // URL is not in beautiful format
            $boolRedirect = True;
        }

        if (!is_array($arrContent)) {
            $this->f3->error(500, "ERROR: no record for page");
        }

        //
        // Redirect if necessary
        //
        if ($boolRedirect) {
            $strLocation = sprintf("Location: http://%s/%s/%d/%s.htm",
                $arrSite["URL"],
                $indexPage,
                $arrContent["ID"],
                urlencode(strtolower($arrContent[$fldTitle]))
            );
            // Have search engines update their index
            header("HTTP/1.1 301 Moved Permanently");
            header($strLocation);
            exit;
        }

        //
        // Get navigation bar
        //
        $arrNav = array();
        $arrNavBar = Content::getNavBar($this->f3, $this->site->ID);
        foreach ($arrNavBar as $k => $v) {
            if ($k == $arrContent["ID"]) {
                $arrNav[] = sprintf('<li role="menuitem" class="current">%s</li>', $v[$fldTitle]);
            } else {
                $arrNav[] = sprintf('<li role="menuitem"><a href="/%s/%d/%s.htm">%s</a></li>', $indexPage, $k, urlencode(strtolower($v[$fldTitle])), $v[$fldTitle]);
            }
        }

        //
        // Populate template
        //
        $this->f3->mset($arrSite);
        $this->f3->set("SiteTitle", $this->site->Title);
        $this->f3->set("ID", $arrContent["ID"]);
        $this->f3->set("Title", $arrContent[$fldTitle]);
        $this->f3->set("Template", $strTemplate);
        $this->f3->set("Descr", $strDescr);
        $this->f3->set("Content", $this->parseData($fldData));
        $this->f3->set("Nav", '<ul role="menubar">' . join("\n", $arrNav) . "</ul>");
        $this->f3->set("Lang", $lang);
        $this->f3->set("LangAltDescr", $strLangAltDescr);
        $this->f3->set("LangAlt", $strLangAlt);
        $this->f3->set("IndexPageAlt", $strIndexPageAlt);
        if (isset($arrSite["Lang2"])) {
            $this->f3->set("LangAltHref", $this->tpl->render(sprintf("multisite/snippets/%s_langalt.tpl", $strTemplate)));
            $this->f3->set("LangAltLink",
                sprintf('<link rel="alternate" hreflang="%s" href="http://%s/%s/%d/">',
                $strLangAlt, $arrSite["URL"], $strIndexPageAlt, $arrContent["ID"]
            ));
        } else {
            $this->f3->set("LangAltHref", "");
            $this->f3->set("LangAltLink", "");
        }
        $this->f3->set("URL", $arrSite["URL"]);
        $this->f3->set("IndexPage", $indexPage);
        $this->f3->set("BgImg", $arrContent["BgImg"]);
        $this->f3->set("BgTxt", $arrContent[$fldBgTxt]);
        $this->f3->set("AG_STATIC_URL", $this->f3['setup']['AG_STATIC_URL']);
        $this->f3->set("Header", $arrSite["Header"]);
        $this->f3->set("ExtraHeader", $arrContent["ExtraHeader"]);
        $this->f3->set("Head", $this->tpl->render("multisite/snippets/head.tpl"));
        return $this->tpl->render("multisite/".$strTemplate.".tpl");
    }

    /**
     * Update action
     *
     * @param   int     $intID
     * @param   array   $arr     $_POST
     * @return  Boolean
     */
    function update(int $intID, array $arr): bool {
        $arrUpdate = array_map('trim', $arr);
        $arrUpdate["ID"] = $intID;
        $arrUpdate["SiteID"] = $this->site->ID;

        // Remove script tags from data fields
        foreach (Content::dataFields as $strFld) {
            if (isset($arrUpdate[$strFld]) && strlen($arrUpdate[$strFld])>0) {
                $arrUpdate[$strFld] = preg_replace('/<script(.*?)>(.*?)<\/script>/is', '', $arrUpdate[$strFld]);
            }
        }

        // Remove all tags from Title
        $arrUpdate["Title"] = strip_tags($arrUpdate["Title"]);

        // Password set?
        if (isset($arrUpdate["UrlPasswdYN"]) && strlen($arrUpdate["UrlPasswd"]) == 0) {
            $arrUpdate["UrlPasswd"] = Content::genUrlPasswd(20);
        } elseif (!isset($arrUpdate["UrlPasswdYN"])) {
            $arrUpdate["UrlPasswdYN"] = 0;
            $arrUpdate["UrlPasswd"] = "";
        }
        if ($arrUpdate["UrlPasswdYN"] == 1) {
            $arrUpdate["NavBarYN"] = 0;
            $arrUpdate["NavBarPosAfterID"] = 0;
        }

        // Checkboxes and dependencies
        if (isset($arrUpdate["AlbumYN"]) && intval($arrUpdate["AlbumDir"]) > 0) {
            // pass
        } else {
            $arrUpdate["AlbumYN"] = 0;
            $arrUpdate["AlbumDir"] = null;
        }
        $arrUpdate["NavBarYN"] = isset($arrUpdate["NavBarYN"]) ? 1 : 0;
        $arrUpdate["NavBarPosAfterID"] = isset($arrUpdate["NavBarYN"]) && isset($arrUpdate["NavBarPosAfterID"]) ? intval($arrUpdate["NavBarPosAfterID"]) : null;

        // Save data
        $this->content->load(['ID = ? AND SiteID = ?', $intID, $this->site->ID]);
        if ($this->content->loaded() == 1) {
            $this->content->copyFrom($arrUpdate);
            $this->content->update();
            $this->redirect = true;
            $this->msg = 'OK: Saved changes';
            return true;
        } else {
            return false;
        }
    }

    /***
     * Parse data before the frontend version of current page is rendered, i.e.
     * - publish an album referred to by this page (if any)
     * - hyperlink email addresses
     * - hyperlink user files and web addresses
     *
     * @param  $strFld          field in tblContent containing the data
     * @return string
     * @see    tblContent.Data  <-- data field for default language
     * @see    tblContent.Data2 <-- data field for alternative language
     */
    protected function parseData(string $strFld="Data"): string {
        assert(in_array($strFld, Content::dataFields));
        assert($this->content->loaded() == 1);
        assert($this->site->loaded() == 1);

        $arrContent = $this->content->cast();
        $arrSite = $this->site->cast();

        // Publish album on page
        if ($arrContent["AlbumYN"] == 1) {
            $strFilePath = sprintf("%s/%s/%s", $this->f3['setup']['AG_WEB_ROOT'], $arrSite["Dir"], $this->f3['setup']['AG_ALBUM_DIR']);
            $objDir = new Dir($this->f3, $strFilePath . "/" . $arrContent["AlbumDir"]);
            $arrFile = $objDir->getFiles($arrContent["AlbumDir"] . "/");
            $arrContent[$strFld] .= join("\n", $arrFile);
        }
        $strContent = $arrContent[$strFld];

        // Hyperlink email address
        $strContent = preg_replace_callback(
            '/([\w\.-]+@[\w\.-]+\.[a-z]{2,})/i',
            function ($matches) {
                $strEmail = HTML::toNCR($matches[1]);
                return sprintf('<a href="mailto:%s">%s</a>', $strEmail, $strEmail);
            },
            strval($strContent)
        );

        // Let .jpg file name display the image
        If ($arrContent["AlbumYN"]) {
            $strReplace = sprintf(
                '<figure><a class="lightbox" title="$1" href="/%s/$1">' .
                '<img src="/%s/$1" alt="$1"></a></figure>',
                $this->f3['setup']['AG_ALBUM_DIR'],
                $this->f3['setup']['AG_ALBUM_DIR']
            );
        } else {
            $strReplace = sprintf(
                '<figure><img src="/%s/$1" alt="$1"></figure>',
                $this->f3['setup']['AG_FILE_DIR']
            );
        }
        $strContent = preg_replace(
            '/([\w\/-]+\.(jpg|jpeg|png|gif))/i',
            $strReplace,
            $strContent
        );

        // Let .pdf file name render a link
        $strContent = preg_replace(
            '/([^"])([\w-]+)(\.pdf)([^"])/i',
            '$1<a href="/' . $this->f3['setup']['AG_FILE_DIR'] . '/$2$3" target="_blank">$2</a>$4',
            $strContent
        );

        // Hyperlink web address
        $strContent = preg_replace(
            '/([^"])((http|https):\/\/[\w.\-\/\%]+)([^"])/i',
            '$1<a href="$2" target="_blank">$2</a>$4',
            $strContent
        );
        return $strContent;
    }
}
?>
