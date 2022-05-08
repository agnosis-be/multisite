<tr>
    <td width="200">
        <kbd><?=$File?></kbd>
    </td>
    <td height="50" width="75">
        <div style="height: 24px; width: 36px">
            <img src="<?=$Src?>" height="100%" width="100%">
        </div>
    </td>
    <td align="left" width="100">
            <input type="submit" class="button delete" name="delete[<?=$File?>]" title="Delete" value="" onclick="return window.confirm('Delete <?=$File?>?')">
    </td>
</tr>

