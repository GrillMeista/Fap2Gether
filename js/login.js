$(document).ready(function () {

    $('#bb').click(function () {

        $.ajax({

            url : 'ajaxHandler.php/Auth/login',
            type : 'POST',
            data : $('#form_control').serialize(),
            dataType:'json',
            success : function(data) {
                if(data.status == 200){
                    window.location.href = 'secret.php';
                }
            },
            error : function(request,error)
            {
                alert("Request: "+JSON.stringify(request));
            }
        });

    });

});