
<table class="topnav">
    <tr>
        <td>
            <input tabindex="-1" type="button" class="button edit" title="My Pages" onclick="location.href='?c=page&amp;a=list'">
        </td>
        <td>
            Edit the content of pages, and publish images, documents or albums (uploaded below)
        </td>
    </tr>
    <tr>
        <td>
            <input tabindex="-1" type="button" class="button upload" title="My Files" onclick="location.href='?c=file&amp;a=list'">
        </td>
        <td>
            Upload individual images (<kbd>.jpg</kbd>) or documents (<kbd>.pdf</kbd>), to be displayed or hyperlinked on a page
        </td>
    </tr>
    <tr>
        <td>
            <input tabindex="-1" type="button" class="button camera" title="My Albums" onclick="location.href='?c=album'">
        </td>
        <td>
            Upload a set of images (<kbd>.jpg</kbd>), to be published as album on a page
        </td>
    </tr>
    <tr>
        <td>
            <input tabindex="-1" type="button" class="button tools" title="My Settings" onclick="location.href='?c=site'">
        </td>
        <td>
            Change layout of my site
        </td>
    </tr>
    <tr>
        <td>
            <form method="post">
                <input type="hidden" name="c" value="auth">
                <input type="hidden" name="a" value="logout">
                <input tabindex="-1" type="submit" class="button close" title="Logout" value="" onclick="return window.confirm('Do you want to logout?')">
            </form>
        </td>
        <td>
            Logout
        </td>
    </tr>
</table>

