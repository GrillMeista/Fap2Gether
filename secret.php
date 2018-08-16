<?php

session_start();

if(!$_SESSION['email']){
    header('Location : login.php');
}

?>

<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {

                function changeSite(site)
                {
                    window.location.href = site;
                }

                $('#create_lobby').click(function () {

                    $.ajax({
                        method: "POST",
                        url: "ajaxHandler.php/Lobby/create",
                        dataType : "json",
                    })
                        .done(function( data ) {

                            if(data.status == 200){
                                changeSite('lobby.php');
                            }

                        });

                });

                $('#join_lobby').click(function () {

                    $.ajax({
                        method: "POST",
                        url: "ajaxHandler.php/Lobby/join",
                        data: { lobbykey: $('input[name=lobbykey]').val() },
                        dataType : "json",
                    })
                        .done(function( data ) {
                            if(data.status == 200){
                                changeSite('lobby.php');
                            }

                        });

                });

            });
        </script>
    </head>
    <body>
        <h5>Welcome
        <?php
            echo ($_SESSION['admin'] == 1) ? "Admin(" . $_SESSION['email'] . ")" : $_SESSION['email'];
        ?>
        </h5>

        <button id="create_lobby">Create Lobby</button><br/>
        <input type="text" name="lobbykey" placeholder="Lobbykey" autofocus>
        <button id="join_lobby">Join Lobby</button>

    </body>
</html>
