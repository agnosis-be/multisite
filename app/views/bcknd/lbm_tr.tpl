<tr>
    <td>
        <input type="button" class="button edit" title="Edit" onclick="location.href='?c=file&amp;album=<?=$ID?>'">
    </td>
    <td>Album <?=$ID?></td>
    <td>
        <input tabindex="-1" name="del[<?=$ID?>]" type="submit" class="button delete" title="Delete" value="" onclick="return window.confirm('Delete album <?=$ID?>?')">
    </td>
    <td>&nbsp;</td>
    <td><?=$Title?></td>
</tr>
