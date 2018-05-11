<html>
<h1>Strona logowania</h1>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<table>
    <td>
<form action="Page_main.php" method="post" name="page_login">
    <label>Podaj e-mail:
        <input id="email" type="text" name ="email">
    </label><br>
    <label>Podaj hasło:
        <input id="haslo" type="text" minlength="1" name ="password">
    </label><br>

    <input type="submit" value ="Zaloguj się" id="exit">
</form></td>
        <td>
<form action="../../indeks.php" method="post" name="page_new_user">
    <input type="submit" value ="Strona startowa">
</form>
<form action="Page_user_create.php" method="post" name="page_new_user">
    <input type="submit" value ="Rejestracja">
</form>
    </td>
</table>
<p id="prompt"></p>
<script>


function ajaxianQuery(email, haslo) {
    var result;
    $.ajax({
        type: "POST",
        url: "../json_commands.php",
        dataType: "html", // oczekuje tekstu, wartość logiczna się źle przekazuje
        async: false, // ----------- !!!
        data: {
            email: email,
            haslo: haslo,
            option: "login"
        }
    }).done(function (response) {
        result = response;
    }) ;
    return result;
}

$(function() {
    var subm = document.getElementById("exit").parentElement;
    $('#prompt').text("");
    // document.getElementById("email").value("em@ber.com 1");
    // document.getElementById("haslo").value("bb");

    subm.addEventListener('submit', function(event) {


        var email = document.getElementById("email").value;
        var haslo = document.getElementById("haslo").value;

        if (email.length == 0 || haslo.length == 0) {
            // alert("email (pustka): " + email + " haslo: " + haslo);
            event.preventDefault();
            $('#prompt').text("Podaj login i hasło");
        } else {

            var answer = ajaxianQuery(email, haslo);

            if (answer === "no") {
//            alert ("Nie znaleziono: " + answer) ;
                event.preventDefault();
                $('#prompt').text("Błędny login lub hasło");
            } else if (answer === "yes") {
//            alert ("Znaleziono: " + answer);
            } else {
                //  undefined gdy błąd odczytu z bazy
                alert("coś nie tak: " + answer);
            }
        }
    });
});

</script>
</html>
