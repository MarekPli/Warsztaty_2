<?php
session_start();
$_SESSION['msg_single'] = $_POST['msg_single'];
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>

<h1>Strona pojedynczej wiadomości</h1>
<p id="prompt"></p>
<form action="Page_msg.php" method="post" name="return">
    <input type="submit" value="Powrót do strony z wiadomościami">
</form>

<script>
    function ajaxianSingleMessage(){
        $.ajax({
            type: "POST",
            url: "../json_commands.php",
            dataType: "json",
            async: false, // ----------- !!!
            data: {
                option: "msg_single"
            }
        }).done(function (response) {
            result = response;
            $("#prompt").html(response);
            // alert(response); // no pewnie że ciągle sprawdzałem!
        }) ;
    }


$(function() {
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
    ajaxianSingleMessage();
});
</script>

</html>


