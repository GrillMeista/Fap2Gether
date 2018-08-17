<?php

session_start();

if(!isset($_SESSION['email'])){
    header('Location : login.php');
}

?>

<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            $.ajax({
                method: "POST",
                url: "ajaxHandler.php/Lobby/getUser",
                dataType : "json",
            })
                .done(function( data ) {

                    if(data.status == 200){
                        $('#admin').append('<div style="border-style: solid; border-color: red; border-radius: 2px;"><img src="images/icon.png" width="50" height="50"><br/><p>' + data.data.admin + '</p></div>');

                        for(let i = 0; i < data.data.members.length; i++){
                            $(' #members').append('<div style="border-style: solid; border-color: grey; border-radius: 2px;">' + data.data.members[i] + '</div>');
                        }

                    }

                });

            $('#profile').click(function () {
                window.location.href = 'profile.php';
            });

        });
    </script>
</head>
<body>
    <div style="width: 5%" id="admin"></div>
    <div style="width: 5%" id="members"></div>

    <button id="profile">Profile</button>
</body>
</html>
