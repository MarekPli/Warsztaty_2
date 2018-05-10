<?php
session_start();
$_SESSION['user'] = $_POST['user'];
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<table>
    <td>
    <p>Użytkownik:<br>
        <?php echo $_SESSION['username']?></p>
    </td><td></td><td>
        <p id="datename">
        </p>
    </td>
    <td>
        <form action="test1.php" method="post" >
            <input type="submit" name ="logout" value="Wyloguj się">
        </form>
        <form action="Page_main.php" method="post" >
            <input type="submit" name ="comeback" value="Strona główna">
        </form>
    </td>
</table>
<form action="Page_user.php" method="post" >
    <label>Do strony wyświetlania użytkownika:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>
<form action="Page_tweet.php" method="post" >
    <label>Do strony wyświetlania wpisu (tweeta):
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>
<form action="Page_user_edit.php" method="post" >
    <label>Do strony edycji użytkownika:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>
<form action="Page_user_msg.php" method="post" >
    <label>Do strony wiadomości:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>

<h1>Strona wyświetlania użytkownika: <?php echo $_SESSION['user']?> </h1>
<p id="user_tweets"></p>
<p id="prompt"></p>
<form action="">
    <input type="text" size="40">
<input type="submit" value="Wyślij wiadomość do <?php echo $_SESSION['user']?>">
</form>

<script>
function ajaxianUserTweets(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "test1.php",
        dataType: "json",
        async: false, // ----------- !!!
        data: {
            option: "user_tweets"
        }
    }).done(function (response) {
        result = response;
        $("#user_tweets").html(prompt + response);
        // alert(response); // no pewnie że ciągle sprawdzałem!
    }) ;
    return result;
}

function ajaxianGetDatename(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "test1.php",
        dataType: "html",
        async: false, // ----------- !!!
        data: {
            option: "datename"
        }
    }).done(function (response) {
        result = response;
        $("#datename").html(prompt + response);
    }) ;
    return result;
}


$(function() {
    // ajaxianGetSesssionUsername("Użytkownik: <br>");
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
    // var p = document.getElementById('#user');
    var arrTweets = ajaxianUserTweets('Tweety użytkownika<br><br>');
});

</script>
</html>