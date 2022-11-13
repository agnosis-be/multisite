<?php
// This file: /app/controllers/AuthController.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.1

/***
 * Authorization controller
 *
 * @see '/www/bcknd/index.php' <-- caller
 * @see '/www/bcknd/login.php' <-- caller
 *
 */
class AuthController {

    protected Base $f3;
    protected View $tpl;
    protected Site $site;
    protected string $msg;

    /**
     * Constructor
     *
     */
    function __construct(Base $f3, View $tpl, Site $site) {
        $this->f3 = $f3;
        $this->tpl = $tpl;
        $this->site = $site;
        $this->msg = "";
    }

    /**
     * Login action
     *
     */
    function login(string $user, string $password): bool {
        $arrContent = array();
        $success = false;

        if (strlen($user)>0 && strlen($password)>0) {
            $this->site->load(["Login = ?", $user]);
            if ($this->site->loaded() == 1) {
                if ($this->site->AllowLoginYN == 1 && password_verify($password, $this->site->Passwd)) {
                    // Login success
                    $success = true;
                    $_SESSION["SITE_ID"] = $this->site->ID;
                    $_SESSION["SITE_DIR"] = $this->site->Dir;
                    $_SESSION["SITE_URL"] = $this->site->URL;
                    $_SESSION["SITE_HOME"] = $this->site->HomeID;
                    $_SESSION["SITE_LANG"] = $this->site->Lang;
                    $_SESSION["SITE_LANG2"] = $this->site->Lang2;
                    $this->site->LastLoginDt = date("Y-m-d H:i:s");
                    $this->site->LastFailCount = 0;
                    $this->site->update();
                    return true;
                } else {
                    // Login failure
                    $this->site->LastFailDt = date("Y-m-d H:i:s");
                    $this->site->LastFailCount++;
                    $this->site->update();
                }
            }
            if (!$success) {
                $this->msg = "User name and password do not match";
                $arrContent["Login"] = $user;
                $arrContent["LoginTitle"] = "User name must be correct";
                $arrContent["LoginClass"] = "error";
                $arrContent["PasswdTitle"] = "Password must be correct";
                $arrContent["PasswdClass"] = "error";
                $this->f3->mset($arrContent);
                return false;
            }
        } else {
            // Login incomplete
            $this->msg = "Please provide both user name and password";
            if (strlen($user) == 0) {
                $arrContent["LoginTitle"] = "User name must not be empty";
                $arrContent["LoginClass"] = "error";
            }
            if (strlen($password) == 0) {
                $arrContent["PasswdTitle"] = "Password must not be empty";
                $arrContent["PasswdClass"] = "error";
            }
            $arrContent["Login"] = $user;
            $arrContent["Passwd"] = $password;
            $this->f3->mset($arrContent);
            return false;
        }
    }

    /**
     * Logout action
     *
     */
    function logout(): bool {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        return session_destroy();
    }

    /**
     * Show action for login form
     *
     */
    function show() {
        $this->f3->set("PageTitle", "Login");
        $this->f3->set("Msg", $this->msg);
        $this->f3->set("MsgOnClick", "ag_ToggleLock('')");
        $this->f3->set("BodyOnLoad", "document.forms[0].elements['Login'].focus()");
        if (!$this->msg) {
            $this->f3->set("Passwd", "");
            $this->f3->set("PasswdClass", "");
            $this->f3->set("PasswdTitle", "");
            $this->f3->set("Login", "");
            $this->f3->set("LoginClass", "");
            $this->f3->set("LoginTitle", "");
        }
        $this->f3->set("TopNav", "");
        $this->f3->set("Content", $this->tpl->render("bcknd/lgn.tpl"));
        echo $this->tpl->render("bcknd/site.tpl");
    }
}
?>
