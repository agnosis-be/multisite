
<form method="post">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="UrlPasswd" value="<?=$UrlPasswd?>">
<input type="hidden" name="c" value="page">
<input type="hidden" name="a" value="update">

<p>
    <input type="button" class="button back" title="My Pages" onclick="location.href='?c=page&amp;a=list'">
    <input type="submit" class="button save" title="Save" value="">
    <input type="button" class="button open" title="View" onclick="window.open('<?=$WebURL?>')">
</p>

<div id="col1">

<fieldset style="width: 95%">
<legend>Content (<?=$Lang?> version)</legend>
<input type="text" class="text" name="Title" style="width: 50%;" value="<?=$Title?>">
<br>
<textarea class="mceEditor" name="Data" style="height: 400px; width: 100%;"><?=$this->raw($Data)?></textarea><br>
</fieldset>

<?=$EditorLang2?>

<fieldset style="width: 95%">
<legend>My Page options</legend>
<input name="AlbumYN" value="1" type="checkbox" onclick="ag_ToggleControl(arrCtrl, this.form);" <?=$AlbumYN_checked?>>
Publish My Album on My Page
<select name="AlbumDir">
    <?=$AlbumDirOptions?>
</select>
<input type="button" class="button camera" Title="Edit Album" onclick="location.href='?c=file&amp;a=list&amp;album=<?=$AlbumDir?>&from=<?=$id?>'"><br>
<input name="NavBarYN" value="1" type="checkbox" onclick="ag_ToggleControl(arrCtrl, this.form)" <?=$NavBarYN_checked?>>
    Display Link to My Page in Navigation Bar after
    <select name="NavBarPosAfterID">
    <?=$NavBarPosAfterIDOptions?>
    </select><br>
<input name="UrlPasswdYN" value="1" type="checkbox" <?=$UrlPasswdYN_checked?>>
Secure My Page with Password in URL
</fieldset>

</div>

<div id="col2">

<p style="font-size: 10pt;">
Using the name of an uploaded file on My Page, will automatically display the image (<kbd>.jpg</kbd>) or render a link to the document (<kbd>.pdf</kbd>):
</p>

<div style="text-align: right;">
    <input type="button" class="button upload" title="Edit My Files" onclick="location.href='?c=file&amp;a=list&amp;from=<?=$id?>'">
</div>

<div id="files">
<table cellspacing="0" cellpadding="0">
<?=$Files?>
</table>
</div>

</div>

<br style="clear: both;">

</form>

