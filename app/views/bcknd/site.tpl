<!DOCTYPE html>
<html>
<head>
<title><?=$PageTitle?></title>
<meta charset="<?=$ENCODING?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="static/style/main.css">
<script type="text/javascript" src="static/js/main.js"></script>
<!-- TinyMCE v. 3.5.12 -->
<!-- https://www.tiny.cloud/docs-3x/reference/for-dummies/ -->
<script src="static/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
  mode : "textareas",
  theme : "advanced"
});
</script>
<!-- /TinyMCE -->
<script type="text/javascript">
var arrCtrl = new Object;
arrCtrl['AlbumYN'] = 'AlbumDir';
arrCtrl['NavBarYN'] = 'NavBarPosAfterID';
</script>
</head>
<body onload="<?=$BodyOnLoad?>">
<div id="Page">

<?=$TopNav?>

<h1><?=$PageTitle?></h1>

<?=$Content?>

</div>

<div id="Lock" style="display: none;"></div>
<div id="Msg" style="display: none;">
    <span id="MsgTxt"></span>
    <input type="button" class="button close" onclick="<?=$MsgOnClick?>" title="Close">
</div>

<script type="text/javascript">
ag_ToggleLock('<?=$Msg?>');
</script>

</body>
</html>
