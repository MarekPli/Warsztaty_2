<!DOCTYPE html>
<?php
require_once(__DIR__ . '/table_test.php');
?>
<h1>Testowanie wiadomości</h1>
<form action="testMessage.php" method="post" >
    <label>Utworzenie nowej wiadomości:
        <input type="submit" name ="create_msg">
    </label>
</form>
<form action="testMessage.php" method="post" >
    <label>Redakcja wybranej wiadomości:
        <input type="number" name ="modify_msg">
        <input type="submit">
    </label>
</form>
<form action="testMessage.php" method="post" name="get">
    <label>Usunięcie wybranej wiadomości:
        <input type="number" name="delete_msg">
        <input type="submit" name="">
    </label>
</form>
<form action="testMessage.php" method="post" >
    <label><b>Wyświetlenie wszystkich wiadomości:</b>
        <input type="submit" name ="all_msg">
    </label>
</form>
<br><br>
</htm>