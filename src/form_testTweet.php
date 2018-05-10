<!DOCTYPE html>
<?php
require_once(__DIR__ . '/table_test.php');
?>
<h1>Testowanie tweetów</h1>
<form action="testTweet.php" method="post" >
    <label>Utworzenie nowego tweeta:
        <input type="submit" name ="create_tweet">
    </label>
</form>
<form action="testTweet.php" method="post" >
    <label>Redakcja wybranego tweeta:
        <input type="number" name ="modify_tweet">
        <input type="submit">
    </label>
</form>
<form action="testTweet.php" method="post" name="get">
    <label>Usunięcie wybranego tweeta:
        <input type="number" name="delete_tweet">
        <input type="submit" name="">
    </label>
</form>
<form action="testTweet.php" method="post" >
    <label><b>Wyświetlenie wszystkich tweetów:</b>
        <input type="submit" name ="all">
    </label>
</form>
<br><br>
</htm>