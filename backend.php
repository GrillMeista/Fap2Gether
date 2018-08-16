<?php
    session_start();

    if(!$_SESSION['email']){
        header('Location : login.php');
    }
?>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
    <h1>Welcome <?php echo $_SESSION['email'];?></h1>
</body>
</html>

