<?php
// This file: /app/controllers/SiteController.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1
require_once(AG_INC_DIR.'/Dir.class.php');
require_once(AG_INC_DIR.'/HTML.class.php');

/***
 * Site controller
 *
 * Gives user access to certain settings of his website,
 * i.e. attributes in tblSite
 *
 * Currently, the user may change:
 * - tblSite.Template          <-- the website's template/design
 *
 * TODO:
 * - [ ] Give user more control through UI
 *
 * @see '/www/bcknd/index.php' <-- caller
 *
 */
class SiteController {
    protected Base $f3;
    protected View $tpl;
    protected Site $site;
    protected Dir $dir;

    protected bool $redirect;
    protected string $msg;
    protected array $tplList;

    /**
     * Constructor
     *
     */
    function __construct(Base $f3, View $tpl, Site $tblSite) {
        $this->f3 = $f3;
        $this->tpl = $tpl;
        $this->site = $tblSite;
        $this->dir = new Dir($this->f3, $this->f3['setup']['AG_INC_DIR'], "/views/multisite");

        $this->redirect = false;
        $this->msg = "";

        $this->tplList = array();
        $_tplList = $this->dir->getFiles();
        foreach ($_tplList as $k => $v) {
            $this->tplList[$v] = $v;
        }
    }

    /**
     * Show action
     *
     */
    function show(int $id) {
        $this->site->load(["ID = ?", $id]);

        $this->f3->set("PageTitle", "My Settings");
        $this->f3->set("BodyOnLoad", "");
        $this->f3->set("TopNav", $this->tpl->render("bcknd/topnav.html"));
        $this->f3->set("Msg", $this->msg);
        $this->f3->set("WebURL", sprintf("http://%s/index.php", $this->site->URL));
        $this->f3->set("TemplateOptions", HTML::toOptions($this->tplList, $this->site->Template));
        $this->f3->set("MsgOnClick", $this->redirect ? "location.href='index.php?c=site'" : "ag_ToggleLock('')");
        $this->f3->set("Content", $this->tpl->render("bcknd/sttng.tpl"));
        echo $this->tpl->render("/bcknd/site.tpl");
    }

    /**
     * Update action
     *
     */
    function update(int $id, array $arr): bool {
        $this->site->load(["ID = ?", $id]);
        if ($this->site->loaded() == 1) {
            if (in_array($arr["Template"], $this->tplList)) {
                $this->site->Template = $arr["Template"];
                $x = $this->site->update();
                $this->redirect = true;
                $this->msg = "OK: Saved changes";
            } else {
                $this->redirect = true;
                $this->msg = "INFO: Nothing changed";
            }
            return true;
        } else {
            $this->msg = "ERROR: Could not save changes";
            return false;
        }
    }
}
?>
