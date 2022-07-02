<?php
@$mail=$_POST['mail'];
@$pass=$_POST['pass'];
@$user=$_POST['user'];
@$btnin=$_POST['btnin'];
@$btngol=$_POST['btngol'];
        include 'cnx.php';
        session_start();
        if(isset($_POST['btnin'])){
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $msg= "Invalid email format";
          }
          else{
               
              $query=mysqli_query($link,"select * from client where username='$user'");
              if(mysqli_num_rows($query)>0){
                  $msg= "username deja exsite";
              }
              else{
                     $query="INSERT INTO client(username,mail,pass) VALUES('$user','$mail','$pass')";
                     $result=mysqli_query($link,$query);
                     $msg= "bien enregistrer";
              }
       }
    }

    if(isset($_POST['btngol'])){
      $query=mysqli_query($link,"select * from client where username ='$user' and pass='$pass'");
      if(mysqli_num_rows($query)==0){
          $msg= "Verify your adresse email or password ";  
      }
          else{
            $_SESSION['usernameClient']=$user;
              header('location:main.php');
          }
      }

        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecElec.com</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="page.css">

</head>
<body>

<!-- header section starts  -->

<header>

    <input type="checkbox" name="" id="toggler">
    <label for="toggler" class="fas fa-bars"></label>

    <a href="#" class="logo">TecElec<span>.</span></a>


</header>

<!-- header section ends -->

<!-- home section starts  -->

            <div class="row">
                <div class="col-1">
                    <div class="form-box">
                        <div class="form">
                            <form class="login-form" method="post">
                                <center><h1 class="main-heading">Login Form</h1></center>
				                <input type="text" placeholder="user name" name="user" />
				                <input type="password"placeholder="password"  name="pass" />
				                <button type="submit" name="btngol" >LOGIN</button>
				                <p class="message">Not Registered? <a href="#">Register</a></p>
				            </form>
                            <form class="register-form" method="post">
                                <center><h1 class="main-heading">Register Form</h1></center>
				                <input type="text" placeholder="user name" name="user"/>
				                <input type="text" placeholder="email-id" name="mail"/>
				                <input type="password" placeholder="password" name="pass"/>
				                <button type="submit" name="btnin">REGISTER</button>
				                <p class="message">Already Registered?<a href="#">Login</a>
				                </p>
				            </form>
			             </div>
	                </div>
                </div>
                <div class="col-1" >
                <?php
        if(isset($msg)){
				?>
				<p class='defination'style="color:red;">
					<?php echo $msg;
						unset($msg);
					?>
					</p>
				<?php
			}?>
                </div>
            </div>
        </div>
    </div>
    <script src='https://code.jquery.com/jquery-3.2.1.min.js'>
    </script>
    <script>
        $('.message a').click(function(){$('form').animate({height: "toggle",opacity: "toggle"},"slow");});
    </script>
</body>
</html>


