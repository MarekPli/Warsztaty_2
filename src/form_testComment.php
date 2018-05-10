<!DOCTYPE html>
<?php
require_once(__DIR__ . '/table_test.php');
?>
<h1>Testowanie komentarzy</h1>
<form action="testComment.php" method="post" >
    <label>Utworzenie nowego komentarza:
        <input type="submit" name ="create_comment">
    </label>
</form>
<form action="testComment.php" method="post" name="get">
    <label>Usunięcie wybranego komentarza:
        <input type="number" name="delete_comment">
        <input type="submit" name="">
    </label>
</form>
<form action="testComment.php" method="post" name="get">
    <label>Redakcja wybranego komentarza:
        <input type="number" name="modify_comment">
        <input type="submit" name="">
    </label>
</form>
<form action="testComment.php" method="post" >
    <label><b>Wyświetlenie wszystkich komentarzy:</b>
        <input type="submit" name ="all_comments">
    </label>
</form>
<form action="testComment.php" method="post" >
    <label><b>inne:</b>
        <input type="submit" name ="inne">
    </label>
</form>
<br><br>
</html>