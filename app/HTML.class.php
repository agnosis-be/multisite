<?php
// This file: /app/HTML.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Class methods to facilitate the output of HTML
 *
 */
class HTML {
    
    /***
     * Create HTML options from given array
     *
     */
    static function toOptions(array $arrIn, string $strKeySelected=null, string $strNoData="No data"): string {
        $arrOut = array();
        If (!$arrIn) {
            $arrOut[] = sprintf("<option>%s</option>", $strNoData);
        } else {
            $arrOut[] = sprintf("<option></option>");
            foreach ($arrIn as $k => $v) {
                $s = $k == $strKeySelected ? "selected" : "";
                $arrOut[] = sprintf(
                    "<option value='%s' %s>%s</option>",
                    $k, $s, $v
                );
            }
        }
        return join("\n", $arrOut);
    }
    
    /***
     * Create numeric character references (NCR) from given string
     *
     * Used to obscure email addresses
     *
     */
    static function toNCR(string $strIn): string {
        $arrOut = array();
        for ($i=0;$i<strlen($strIn);$i++) {
            $arrOut[] = "&#" . ord($strIn[$i]) . ";";
        }
        return join($arrOut);
    }
}
?>