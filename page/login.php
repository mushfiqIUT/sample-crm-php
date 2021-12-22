
<form name="loginForm" method="POST" action="process.php?do=login">
<table id="login" style="margin: 50px auto; border: 1px solid #b5b5b5; padding: 10px 20px; background: #fafafa;">
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td>Username</td>
        <td>:</td>
        <td>
            <input class="text" type="text" name="user" />
        </td>
    </tr>
    <tr>
        <td>Password</td>
        <td>:</td>
        <td>
            <input class="text" type="password" name="pass" />
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>
            <input type="submit" name="submit" class="button" value="Connect" />
        </td>
    </tr>
</table>
</form>