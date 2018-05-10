<?php
session_start();
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

<h1>Strona edycji użytkownika: <?php echo $_SESSION['username']?></h1>



<script>
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
});
</script>


</html>