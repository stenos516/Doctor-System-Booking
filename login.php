  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\login.css">
    <title>Login</title>
</head>
<body>
<?php

session_start();
include("con.php");

$_SESSION["user"]="";
$_SESSION["usertype"]="";

//set timezone
date_default_timezone_set('Europe/Rome');
$date = date('Y-m-d');

$_SESSION["date"]=$date;


if($_POST) {

    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    
    $error='<label for="promter" class="form-label"></label>';

    $result= $db->query("SELECT * FROM webuser WHERE email='$email'");
    if($result->num_rows==1){
        $utype=$result->fetch_assoc()['usertype'];
        if ($utype=='p'){
            //TODO
            $checker = $db->query("select * from patient where pemail='$email' and ppassword='$password'");
            if ($checker->num_rows==1){


                //   Patient dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='p';
                
                header('location: patient/index.php');

            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }

        }elseif($utype=='a'){
            //TODO
            $checker = $db->query("select * from admin where aemail='$email' and apassword='$password'");
            if ($checker->num_rows==1){


                //   Admin dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='a';
                
                header('location: admin/index.php');

            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }


        }elseif($utype=='d'){
            //TODO
            $checker = $db->query("select * from doctor where docemail='$email' and docpassword='$password'");
            if ($checker->num_rows==1){


                //   doctor dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='d';
                header('location: index.php');

            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }

        }
        
    }else{
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any acount for this email.</label>';
    }






    
}else{
    $error='<label for="promter" class="form-label">&nbsp;</label>';
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title> -->

    <link rel="stylesheet" href="styles.css">
    <style>
        html, body {
            height: 70%;
            margin: 0;
            background: url('26363.jpg') no-repeat center center fixed;
            background-size: cover; /* Use 'contain' if you prefer to see the whole image */
        }
        .container {
            text-align: center;
            color: #fff;
            padding-top: 20%;
        }
        .container h1 {
            font-size: 3em;
        }

        .Title  {
            margin-right: 25px;
            margin-top:1rem;
            text-align:center;
            font-size:4rem;
            color:white;
        }
    </style>
</head>
<body>
<h1 class="Title">Doctor System Booking</h1>
    <div class="login-box">
        <h2>Login</h2>
        
        <form method="post">
            <div class="user-box">
                <input type="text" name="useremail" required="">
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="userpassword" required="">
                <label>Password</label>
            </div>
            <div>
                <?php echo $error ?>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" class="login-btn btn-primary">
                <br>
<a href = "signup.php" >Don't have account?</a>
</div>
        </form>
     
       
    </div>
<!-- Footer -->
<footer>
        <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
    </footer>
</body>
</html>

</body>
</html>