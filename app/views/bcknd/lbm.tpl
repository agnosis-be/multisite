
<form method="post">

<input name="from" type="hidden" value="<?=$From?>">

<p>
<input type="button" class="button back" title="<?=$BtnBackLabel?>" onclick="location.href='<?=$BtnBackURL?>'">
<input type="submit" name="add" class="button add" title="Add album" value="">
</p>

<table>
<?=$Albums?>
</table>

</form>

