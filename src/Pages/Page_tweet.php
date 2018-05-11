<?php
session_start();
$_SESSION['tweet'] = $_POST['tweet'];
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>

<h1>Strona wyświetlania wpisu</h1>
<p id="prompt"></p>
<form action="">
    <input type="text" size="40">
    <input type="submit" value="Napisz nowy komentarz">
</form>

<script>
    function ajaxianFullTweet() {
        var result;
        $.ajax({
            type: "POST",
            url: "../Json_commands.php",
            dataType: "json",
            async: false, // ----------- !!!
            data: {
                option: "full_tweet"
            }
        }).done(function (response) {
            result = response;
            $("#prompt").html(response);
            // alert(response); // no pewnie że ciągle sprawdzałem!
        }) ;
        return result;
    }

    $(function() {
        ajaxianGetDatename("Dzisiejszy dzień to: <br>");
        ajaxianFullTweet();
    });

</script>
</html>