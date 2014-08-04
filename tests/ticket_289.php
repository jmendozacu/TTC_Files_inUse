<?php
/**
 * Created by PhpStorm.
 * User: chrislohman
 * Date: 5/21/14
 * Time: 1:29 PM
 */
$flag = false;
if(isset($_POST['web_user_id']) && $_POST['web_user_id'] != '')
{
    $name = 'store';
    $domain = '.'.$_SERVER['SERVER_NAME'];
    $value = 'userID='.$_POST['web_user_id']. '&StreamEligible=False&email=&firstname=&lastname=';
    $expire = time() + (86400*365);
    setcookie($name, $value, $expire, '/', $domain);
    $flag = true;
}
?>
<html>
<head></head>
<body>
    <div style="margin: 0 auto;">
        <form method="post">
            <table>
                <tr>
                    <th colspan="3" align="center">Ticket #289: Enter Web User Id to receive "Store" cookie</th>
                </tr>
                <tr>
                    <td colspan="3"><b>Step 1:</b> Enter Web User Id you'd like to test with below and click submit</td>
                </tr>
                <tr>
                    <td align="right">Web User ID</td>
                    <td align="center"><input name="web_user_id" value="" size="50"/></td>
                    <td align="center"><input type="submit" value="submit"/></td>
                </tr>
            </table>
        </form>
        <?php if($flag): ?>
        <table>
            <tr>
                <td><b>Step 2:</b>Your cookie was successfully set!<br>You may now proceed to site by clicking <a href="/">here</a>.</td>
            </tr>
        </table>
        <?php endif; ?>
    </div>
</body>