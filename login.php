<?php
session_start();

include("connection.php");
include("function.php");

//checks if the user is already loged in.
$_user_data= check_login($con);

if($_SERVER['REQUEST METHOD'] == "POST"){
    // check if clicked post button

    $_user_name= $_POST['$_user_name'];
    $_password= $_POST['$_password'];

    if (!empty($_user_name) && !empty($_password) && is_numeric($_user_name)){
        # code...
        //saving to the database
        $query= "select*from the user where user_name= '$_user_name' limit 1";

        //setting max entry
        $user_id= random_num(20);

        mysqli_query($con, $query);
        header("Location: login.php");
        die;
    }
    else {
        
        echo"Wrong Password or Username!";
    }
    if ($result && mysqli_num_rows($result)>0) {
        # code...
        $_user_data= mysqli_fetch_assoc($result);

        if ($user_data['password'] === $_password) {
            # code...
            $_SESSION['user_id'] = $user_data['user_data'];
            header("Location: product.php");
            die;
        }
    }

}
?>




<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/x-icon"href="https://image.shutterstock.com/image-vector/favorite-star-icon-260nw-387841594.jpg ">
    </head>
<body>
    <style>
        button:active{
  background-color:green;
}
body{
    background-image:url(./IMAGES/PIC\ 2/image.jpg);
background-repeat: no-repeat;
background-size: cover;
height: 100%;


}

    </style>

<form action="home.html" method="get" style="margin: auto; width: 300px; height: auto; text-align: center; border-style: dashed;padding: 25px;  background-color: aqua;margin-top: 200px;">
    username:<br>
    <input type="name" name="username " placeholder="username" required><br>
    Email Adress:<br>
    <input type="email" name="Email Adress" placeholder="email adress"required><br>
    Password:<br>
    <input type="password" name="Password" placeholder="password"  max length="10" min length="6" required ><br><br>
    
 <button>SUBMIT</button>
</form>


</body>




</html>