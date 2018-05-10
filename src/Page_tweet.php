<?php
session_start();
$_SESSION['tweet'] = $_POST['tweet'];
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
            url: "test1.php",
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
        ajaxianGetDatename("Dzisiejszy dzień to: <br>");
        ajaxianFullTweet();
    });

</script>
</html>