<table>
    <td>
        <p>Użytkownik:<br>
            <?php echo $_SESSION['username']?></p>
    </td><td></td><td>
        <p id="datename">
        </p>
    </td>
    <td>
        <form action="Page_login.php" method="post" >
            <input type="submit" name ="logout" value="Wyloguj się">
        </form>
        <form action="Page_main.php" method="post" >
            <input type="submit" name ="comeback" value="Strona główna">
        </form>
    </td>
</table>
<script>
function ajaxianGetDatename(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "../json_commands.php",
        dataType: "html",
        async: false, // ----------- !!!
        data: {
            option: "datename"
        }
    }).done(function (response) {
        result = response;
        $("#datename").html(prompt + response);
    });
    return result;
}
</script>