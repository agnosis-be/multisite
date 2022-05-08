<tr>
    <td>
        <input type="button" class="button edit" title="Edit" onclick="location.href='index.php?id=<?=$ID?>&amp;c=page&amp;a=show'">
    </td>
    <td>
        <?=$Title?>
    </td>
    <td>
        <?=$Data?>
    </td>
    <td>
        <form method="post">
            <input tabindex="-1" name="del[<?=$ID?>]" class="button delete" type="submit" value="" title="Delete" onclick="return window.confirm('Delete <?=$Title?>?')">
            <input type="hidden" name="c" value="page">
            <input type="hidden" name="a" value="del">
        </form>
    </td>
</tr>
