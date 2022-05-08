
<form enctype="multipart/form-data" method="post">

<p><?=$Msg2?></p>

<input name="album" type="hidden" value="<?=$Album?>">
<input name="from" type="hidden" value="<?=$From?>">

<p>
<input type="button" class="button back" title="<?=$BtnBackLabel?>" onclick="location.href='<?=$BtnBackURL?>'">
<input type="file" name="file[]" multiple>
<input type="submit" class="button save" title="Upload" value="">
</p>

</form>

<form method="post">

<input name="album" type="hidden" value="<?=$Album?>">
<input name="from" type="hidden" value="<?=$From?>">

<table style="table-layout: fixed;">
<?=$Files?>
</table>

<p><?=$Msg3?></p>

</form>

