<?php
session_start();
$_SESSION['user'] = $_POST['user'];
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>


<h1>Strona wyświetlania użytkownika: <?php echo $_SESSION['user']?> </h1>
<p id="user_tweets"></p>
<form action="">
    <input type="text" size="40">
<input type="submit" value="Wyślij wiadomość do <?php echo $_SESSION['user']?>">
</form>

<script>
function ajaxianUserTweets(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "..jaon_commands.php",
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

$(function() {
    // ajaxianGetSesssionUsername("Użytkownik: <br>");
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
    // var p = document.getElementById('#user');
    var arrTweets = ajaxianUserTweets('Tweety użytkownika<br><br>');
});

</script>
</html>