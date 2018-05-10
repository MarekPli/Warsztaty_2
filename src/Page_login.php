<html>
<!--?>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->

<!--<script src="login.js" type="text/javascript"></script>-->

<h1>Strona logowania</h1>
<table>
    <td>
<form action="Page_main.php" method="post" name="page_login">
    <label>Podaj e-mail:
        <input id="email" type="text" name ="email">
    </label>
    <label>Podaj hasło:
        <input id="haslo" type="text" minlength="1" name ="password">
    </label>

    <input type="submit" value ="Zaloguj się" id="exit">
</form></td>
        <td>
<form action="Page_create_user.php" method="post" name="page_new_user">
    <input type="submit" value ="Rejestracja">
</form>
    </td>
    <p id="prompt"></p>
</table>


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script>

    function funcBefore(){
        $("#information").text("Czekam na dane...");
    }

    $(function() {
// document.addEventListener("DOMContentLoaded", function() {
        var subm = document.getElementById("exit").parentElement;

        // function funcSuccess(data){
        //     alert("Sukces: " + data);
        // }
        subm.addEventListener('submit', function(event) {
            // event.preventDefault();
            var email = document.getElementById("email").value;
            var haslo = document.getElementById("haslo").value;
            // alert("email (raz): " +email+ " haslo: " +haslo);
            if (email.length == 0 || haslo.length == 0) {
                alert("email (pustka): " + email + " haslo: " + haslo);
                event.preventDefault();
                var newPara = document.createElement('p');
                newPara.innerText = "Niestety nie udało się";
                subm.appendChild(newPara);
            } else {
                alert('Będzie Ajax..');
                $.ajax({
                    type: "POST",
                    url: "test1.php",
                    dataType: "html",
                    data: {
                        email: email,
                        haslo: haslo
                    },
                    beforeSend: funcBefore,
                    success: function (data)  {
                        alert("Sukces: " + data);
                        $("#information").text = data;
                    }
                });
                // }).done(function (response) {
                //     alert("response: " + response);
                //     if (response == "no") {
                //         event.preventDefault();
                //         alert("email (brak w bazie): " + email + " haslo: " + haslo);
                //     } else {
                //         alert("Znalazłem w bazie: " + response);
                //     }
                //     alert("...był Ajax -- " + response + " no i tak...");
                // }).fail(function(a,b, c) {
                //     event.preventDefault();
                //     alert ("nie zwrócono poprawnej wartości, " +a+ ' ' +b+ ' ' +c);
                // }).always(function (xhr, status) {
                //     alert ("zawsze chodzi o: " + xhr + ' ' +status);
                // });
            }
        });
    });
</script>
<div id="information"></div>

</html>