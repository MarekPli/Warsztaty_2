<?php
//include_once(__DIR__ . '/User.php');
/**
* Created by PhpStorm.
* User: marek
* Date: 24.04.2018
* Time: 21:12
*/
//$pdo = myOpenDatabase('Warsztaty_2');
?>
<!DOCTYPE html>
<h1>Strona logowania</h1>
<table>
    <td>
<form action="Page_main.php" method="post" name="page_login">
    <label>Podaj e-mail:
        <input type="email" name ="email">
    </label>
    <label>Podaj hasło:
        <input type="password" name ="password">
    </label>
    <input type="submit" value ="Zaloguj się">
</form></td>
        <td>
<form action="Page_create_user.php" method="post" name="page_new_user">
    <input type="submit" value ="Rejestracja">
</form>
    </td>
</table>

