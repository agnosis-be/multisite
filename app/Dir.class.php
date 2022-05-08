<?php
// This file: /app/Dir.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/***
 * Directory with (user) files
 *
 * @see    './controllers/AlbumController.class.php' <-- caller (to manage user albums)
 * @see    './controllers/FileController.class.php'  <-- caller (to manage user files)
 * @see    './controllers/PageController.class.php'  <-- caller (to show album on page)
 * @see    './controllers/SiteController.class.php'  <-- caller (to select template/design)
 */
class Dir {

    public array $acceptImageType = array("image/jpeg", "image/pjpeg");
    public array $acceptDocType = array("application/pdf");
    public int $imgMaxWidth = 640;
    public int $imgMaxHeight = 480;

    protected Base $f3;
    protected int $fileSizeQuota;
    protected int $fileDirSizeQuota;
    protected array $acceptType;
    protected string $root;
    protected string $path;
    protected array $msg;

    /***
     * Constructor
     *
     */
    function __construct(Base $f3, string $strRoot, string $strDir=null, $boolAlbum=false) {
        $this->f3 = $f3;
        $this->root = $strRoot;
        $this->fileSizeQuota = $this->f3['setup']['AG_FILE_SIZE_QUOTA'];
        $this->fileDirSizeQuota = $this->f3['setup']['AG_FILE_DIR_SIZE_QUOTA'];
        if (isset($strDir)) {
            $this->path = sprintf("%s/%s", $strRoot, $strDir);
        } else {
            $this->path = $strRoot;
        }
        if ($boolAlbum) {
            $this->acceptType = $this->acceptImageType;
        } else {
            $this->acceptType = array_merge($this->acceptImageType, $this->acceptDocType);
        }
    }

    /***
     * Upload local files
     *
     * HTML:
     *   <input type="file" name="file[]" multiple>
     *
     * @param   Array   $arrFiles       $_FILES array
     * @return  Boolean
     */
    public function upload(array $arrFiles): bool {
        $arrServerFiles = $this->getFiles();

        // Loop through file array
        for ($i=0; $i<count($arrFiles["file"]["name"]);$i++) {
            if (strlen($arrFiles["file"]["name"][$i]) == 0) {
                // No file
                $this->msg[$i] = "ERROR: You have not chosen any file to upload";
                return false;
            } elseif (self::getSize($this->root) > $this->fileDirSizeQuota) {
                // Directory full
                $this->msg[$i] = sprintf(
                    "ERROR: File %s could not be uploaded because " .
                    "the maximum directory size of %d MB has been reached",
                    $arrFiles["file"]["name"][$i],
                    $this->fileDirSizeQuota/1000000
                );
                return false;
            } elseif (!in_array($arrFiles["file"]["type"][$i], $this->acceptType)) {
                // File type not accepted
                $this->msg[$i] = sprintf(
                    "ERROR: %s is not of one of the following types: %s",
                    $arrFiles["file"]["name"][$i],
                    join(", ", $this->acceptType)
                );
            } elseif ($arrFiles["file"]["size"][$i] > $this->fileSizeQuota) {
                // File size to large
                $this->msg[$i] = sprintf(
                    "ERROR: %s exceeds the maximum file size of %d MB",
                    $arrFiles["file"]["name"][$i],
                    $this->fileSizeQuota/1000000
                );
            } elseif (substr($arrFiles["file"]["name"][$i], 0, 1) == ".") {
                $this->msg[$i] = sprintf(
                    "ERROR: file name %s starts with a dot (.), which is not allowed",
                    $arrFiles["file"]["name"][$i]
                );
            } else {
                // File is valid
                $strFileAdd = preg_replace("/[\s]{1,}/", "", $arrFiles["file"]["name"][$i]);
                $arrPathInfo = pathinfo($strFileAdd);
                $strFileExt = $arrPathInfo["extension"];
                $strFileName = $arrPathInfo["filename"];

                // Ensure file name is unique
                $j = 0;
                while (in_array($strFileAdd, $arrServerFiles)) {
                    $j++;
                    $strFileAdd = sprintf("%s_%d.%s", $strFileName, $j, $strFileExt);
                }

                // Resample?
                $boolResample = false;
                if (in_array($arrFiles["file"]["type"][$i], $this->acceptImageType)) {

                    // Get original size
                    list($width_orig, $height_orig) = getimagesize($arrFiles["file"]["tmp_name"][$i]);

                    If ($width_orig > $this->imgMaxWidth || $height_orig > $this->imgMaxHeight) {
                        $boolResample = true;
                    }
                }

                if ($boolResample) {
                    // Keep original ratio
                    $ratio_orig = $width_orig/$height_orig;
                    if ($this->imgMaxWidth/$this->imgMaxHeight > $ratio_orig) {
                       $this->imgMaxWidth = $this->imgMaxHeight*$ratio_orig;
                    } else {
                       $this->imgMaxHeight = $this->imgMaxWidth/$ratio_orig;
                    }

                    // Resample!
                    $image_p = imagecreatetruecolor($this->imgMaxWidth, $this->imgMaxHeight);
                    $image = imagecreatefromjpeg($arrFiles["file"]["tmp_name"][$i]);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $this->imgMaxWidth, $this->imgMaxHeight, $width_orig, $height_orig);

                    // Save resampled file
                    $boolSaved = @imagejpeg($image_p, $this->Path . "/" . $strFileAdd, 100);
                    unset($image_p);
                    unset($image);
                } else {
                    // Save uploaded file
                    $boolSaved = @move_uploaded_file(
                        $arrFiles["file"]["tmp_name"][$i],
                        sprintf("%s/%s", $this->path, $strFileAdd)
                    );
                }
                if ($boolSaved) {
                    $this->msg[$i] = sprintf("OK: %s was uploaded", $arrFiles["file"]["name"][$i]);
                } else {
                    $this->msg[$i] = sprintf("ERROR: %s could not be uploaded", $arrFiles["file"]["name"][$i]);
                    return false;
                }
            }
        }
        return true;
    }

    /***
     * Get files of this directory
     *
     * @param    String   $strPrefix  (prefix to be added to each file found)
     * @return   Mixed
     */
    public function getFiles(string $strPrefix="") {
        $arrFile = array();
        $fDir = opendir($this->path);
        if (!$fDir) return false;
        while($strFile = readdir($fDir)) {
            if (is_file($this->path . "/" . $strFile) && substr($strFile, 0, 1) != ".") {
                $arrFile[] = $strPrefix . $strFile;
            }
        }
        sort($arrFile);
        return $arrFile;
    }

    /***
     * Delete given file
     *
     */
    public function deleteFile(string $strFile): bool {
        $arrFile = $this->getFiles();
        If (in_array($strFile, $arrFile)) {
            $strPathDel = sprintf("%s/%s", $this->path, $strFile);
            If (file_exists($strPathDel)) {
                $deleted = @unlink($strPathDel);
                if ($deleted) {
                    $this->msg[] = sprintf("OK: %s was deleted", $strFile);
                    return true;
                } else {
                    $this->msg[] = sprintf("ERROR: %s could not be deleted", $strFile);
                    return false;
                }
            }
        }
        return false;
    }

    /***
     * Get sub directories
     *
     * @return  Mixed
     */
    function getSubDirs(): array {
        $strDir = $this->path;
        $arrFile = array();
        $fDir =opendir($strDir);
        if (!$fDir) return false;
        while($strFile = readdir($fDir)) {
            If (is_dir($strDir . "/" . $strFile) && $strFile != ".." && $strFile != ".") {
                $arrFile[$strFile] = $strFile;
            }
        }
        asort($arrFile);
        return $arrFile;
    }

    /***
     * Get messages
     *
     */
    public function getMsg(): array {
        return $this->msg;
    }

    /**
     * Get accepted file types
     *
     */
    public function getAcceptType() {
        return $this->acceptType;
    }

    /**
     * Get size of directory
     *
     */
    public static function getSize($path) {
        $result = explode("\t",exec("du -sb ".escapeshellarg($path)),2);
        return ($result[1]==$path ? $result[0] : false);
    }
}
?>
