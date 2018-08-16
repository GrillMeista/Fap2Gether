<?php
?>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            $.ajax({
                method: "POST",
                url: "ajaxHandler.php/Profile/getUser",
                dataType : "json",
            })
                .done(function( data ) {

                    if(data.status == 200){
                        $('input[name=id]').val(data.data.id);
                        $('input[name=username]').val(data.data.username);
                        $('input[name=email]').val(data.data.email);
                    }

                });

            $(document).on('click', "#cancel", function() {

                $('input[name=old_password]').remove();
                $('input[name=new_password]').remove();
                $('input[name=new_password_check]').remove();
                $('input[name=username]').attr("readonly");
                $('input[name=email]').attr("readonly");
                $('#options').text('Edit');
                $(this).remove();

                $.ajax({
                    method: "POST",
                    url: "ajaxHandler.php/Profile/getUser",
                    dataType : "json",
                })
                    .done(function( data ) {

                        if(data.status == 200){
                            $('input[name=id]').val(data.data.id);
                            $('input[name=username]').val(data.data.username);
                            $('input[name=email]').val(data.data.email);
                        }

                    });

            });

            $('#options').click(function () {

                if($('#options').text() == "Edit"){

                    $('input[name=username]').removeAttr( "readonly" );
                    $('input[name=email]').removeAttr( "readonly" );
                    $('#profile_form').append('<input type="password" name="old_password" placeholder="Old password"><br/><input type="password" name="new_password" placeholder="New password"><br/><input type="password" name="new_password_check" placeholder="New password check"><br/>');

                    $('body').append('<button id="cancel">Cancel</button>');

                    $('#options').text("Save");
                }else{

                    $.ajax({
                        method: "POST",
                        url: "ajaxHandler.php/Profile/edit",
                        data: {
                            username: $('input[name=username]').val(),
                            email: $('input[name=email]').val(),
                            oldPassword: $('input[name=old_password]').val(),
                            newPassword: $('input[name=new_password]').val(),
                            newPasswordCheck: $('input[name=new_password_check]').val(),
                        },
                        dataType : "json",
                    })
                        .done(function( data ) {

                            if(data.status == 200){

                                $('input[name=old_password]').remove();
                                $('input[name=new_password]').remove();
                                $('input[name=new_password_check]').remove();
                                $('input[name=username]').attr("readonly");
                                $('input[name=email]').attr("readonly");
                            }

                        });

                    $('#options').text("Edit");
                }
            });

        });
    </script>
</head>
<body>
    <form id="profile_form" method="post">
        <input type="text" name="id" readonly><br/>
        <input type="text" name="username" readonly><br/>
        <input type="email" name="email" readonly><br/>
    </form>
    <button id="options" value="Edit">Edit</button>
</body>
</html>