<!DOCTYPE html>
<?php
require_once(__DIR__ . '/../header_test.php');
?>
<h1>Testowanie użytkowników</h1>
<form action="testUser.php" method="post" name="add">
    <label>Dodanie nowego użytkownika:
        <input type="submit" name ="add_user">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
    <label>Wczytanie użytkownika:
        <input type="number" value="1" min="1" size="5" name="nr_index">
        <input type="submit" name="get_user">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
<label>Zmodyfikowanie wybranego użytkownika:
        <input type="number" value="1" min="1" name="modify_index">
        <input type="submit" name="">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
    <label>Usunięcie wybranego użytkownika:
        <input type="number" value="1" min="1" name="delete_index">
        <input type="submit" name="">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
    <label>Sprawdzenie czy istnieje podany adres mailowy:
            <input type="email" name="is_email">
            <input type="submit" name="">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
    <label>Sprawdzenie czy istnieje podany użytkownik:
        <input type="text" name="is_username">
        <input type="submit" name="">
    </label>
</form>
</form>
<form action="testUser.php" method="post" name="get">
    <label><i>Ujawnij hasła użytkowników (podaj klucz dostępu):</i>
        <input type="text" name="admin" >
        <input type="submit" name="add_all_pass">
    </label>
</form>
<form action="testUser.php" method="post" name="get">
    <label><b>Wczytanie wszystkich użytkowników:</b>
        <input type="submit" name="add_all">
    </label>
</form>
    <br><br>
</html>