<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>

<h1>Strona z wiadomościami</h1>

<form action="Page_msg_single.php" method="post" >
    <label>Pokaż wybraną wiadomość:
        <input type="number" name="msg_single">
        <input type="submit">
    </label>
</form>

<p id="prompt"></p>
<script>

    function ajaxianMessages() {
        var result;
        $.ajax({
            type: "POST",
            url: "../json_commands.php",
            dataType: "json",
            async: false, // ----------- !!!
            data: {
                option: "messages"
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
        ajaxianMessages();
    });


</script>
</html>