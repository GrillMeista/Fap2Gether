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

                        if(parseInt(data.data.time) > 0){
                            document.getElementById("video").currentTime = parseInt(data.data.time);
                        }

                    }

                });

            $('#play').click(function () {

                if($('#play').text() == "Start"){

                    document.getElementById("video").play();
                    $('#play').text("Stop");

                }else{

                    document.getElementById("video").pause();
                    $('#play').text("Start");

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
    <div style="width: 5%" id="members"></div><br/>

    <video id="video" src="video/video.mp4" controls></video><br/>

    <button id="profile">Profile</button><br/>
    <button id="play">Start</button>

</body>
</html>
