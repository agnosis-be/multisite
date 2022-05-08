
<form method="post">

<label for="Login">User name</label>
<input type="text" name="Login" value="<?=$Login?>" class="<?=$LoginClass?>" title="<?=$LoginTitle?>" style="width: 200px;">
<br>
<label for="Passwd">Password</label>
<input type="password" name="Passwd" value="<?=$Passwd?>" class="<?=$PasswdClass?>" title="<?=$PasswdTitle?>" style="width: 200px;">
<br>
<label>&nbsp;</label><input type="submit" value="Login">

</form>

<div style="margin-top: 20px">
A word on data privacy: login only works with session cookies, that is why they are used on this site. Other types of cookies are <em>not</em> used. As to your IP address: it is neither logged nor is it shared with third parties.
</div>
