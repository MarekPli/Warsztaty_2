<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>

<h1>Strona edycji użytkownika</h1>
<p>(puste/wyczyszczone miejsca pozostaną bez zmian)</p>
<form action="" method="post" >
    <label>Nazwa użytkownika:
        <input type="text" name="edit_username" value="<?php echo $_SESSION['username']?>">
    </label><br>
    <label>Adres mailowy:
        <input type="text" name="edit_email" size=25 value="<?php echo $_SESSION['email']?>">
    </label><br>
    <label>Hasło:
        <input type="text" name="edit_haslo" size=34 value="">
    </label><br>
    <input type="submit" name="sub_edit_user">
</form>


<script>


$(function() {
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
});
</script>


</html>

<?php
session_start();
// na to, niezwykle prymitywne, rozwiązanie
// "zastąp element bazy elementami formularza"
// wpadłem pod sam koniec ćwiczenia,
// gdy zrozumiałem że przycisk typu submit
// może samodzielnie przesyłać swoje name
include_once(__DIR__ . '/../Classes/User.php');
include_once(__DIR__ . '/../DB_open.php');

$userEdit = 0;
if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
    // sprawdzę tylko jeden element w POST
    if (isset($_POST['sub_edit_user'])
        && !empty($_POST['sub_edit_user'])
    ) {
        $userEdit = 1;
        // jeszcze nie będzie singletona...
        $pdo = myOpenDatabase('Warsztaty_2');
        $user = User::is_username($pdo, $_SESSION['username']);

//        echo $user->getUsername();

        if (!empty ($_POST['edit_username'])) {
            $user->setUsername($_POST['edit_username']);
            $_SESSION['username'] = $_POST['edit_username'];
        }

        if (!empty ($_POST['edit_email'])) {
            $user->setEmail($_POST['edit_email']);
            $_SESSION['email'] = $_POST['edit_email'];
        }

        if (!empty ($_POST['edit_haslo'])) {
            $user->setPassword($_POST['edit_haslo']);
        }
        // echo "jestem";
//        echo $user->getUsername() . "<br>" .
//            $user->getEmail() . "<br>" .
//            $user->betrayPassword('111');
        $user->saveToDB($pdo); // oczywiście jest to zapis modyfikujący a nie dodający

    } else {
        // echo "nie ma mnie";
    }
}
// ...i to rozwiązanie jest dla mnie na razie wzorcem
?>