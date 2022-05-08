<form method="post">

<p>
<input type="submit" class="button save" title="Save" value="">
<input type="button" class="button open" title="View" onclick="window.open('<?=$WebURL?>')">
</p>

<table>
    <tr>
        <td>Layout of My Site</td>
        <td>
            <select name="Template">
                <?=$TemplateOptions?>
            </select>
        </td>
    </tr>
</table>

</form>
