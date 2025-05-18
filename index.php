<?php 
  // Starting The Session
  session_start();
  // Variblekaan Sawabta Aan u Smaeyey Waa Hadii Wax Redirect aanan lasoo dirin in userka toos loo geeyo deashboard
  $redirect = "dashboard.php";
  // Hadii user login ah uu soo galo dib ugu celi meesha uu ka imaaday
if (isset($_SESSION['isActive']) && $_SESSION['isActive'] == TRUE) {
   
    header("Location:$_SESSION[redirectBack]");
    exit();
}
  // Sessionskaan Waxaa Lagu Ilaalinaa Userka Xugta uu Meesha Kusoo Qorey
  $_SESSION['userEmail']='';
  $_SESSION['userPassword']='';
  // Calling Connectin File
  require("includes/conn.php");
// Default Message
  $message=["display"=>"none","type"=>"","msg"=>""];

if(isset($_POST['loginNow']))
{
  // Extract All Post Methods
  extract($_POST);
     // Form Validation
    if( empty($email) || empty($password))
    {
      $_SESSION['userEmail']=$email;
     $_SESSION['userPassword']=$password;
     $message=["display"=>"block","type"=>"danger","msg"=>"All Fields Are Reuqired!"];
    }
    else
    {
      // Cheack The User As Email
      $select = mysqli_query($conn , "SELECT * FROM users where email = '$email'");
      if($select && mysqli_num_rows($select)>0)
      {
        // Storing The User Data
        $user = mysqli_fetch_assoc($select);
        // Cheack The Password
        if(password_verify($password,$user['password']))
        {
          // Hubi In userkaan Active uu yehe oo lasoo active gareeye
          if($user['status'] == "Active")
          {
          // Send The Sessions And Forward The Users
          $_SESSION['isActive'] = TRUE;
          $_SESSION['activeRole'] = $user['role'];
          $_SESSION['activeUser'] = $user['id'];
          // Forward
       
            if (isset($_SESSION['redirectBack']))
             {
                $redirect = $_SESSION['redirectBack'];
                unset($_SESSION['redirectBack']); // clear the session to avoid redirect loop
                header("Location: $redirect");
                exit();
            }
            else
            {
               header("Location: $redirect");
                exit();
            }
        }
        else
        {
                    $_SESSION['userEmail']=$email;
              $_SESSION['userPassword']=$password;
               $message=["display"=>"block","type"=>"danger","msg"=>"Looks like your account is not active yet. Please hold on while the admin activates it."];
        }
      }
         else
        {
                    $_SESSION['userEmail']=$email;
              $_SESSION['userPassword']="";
               $message=["display"=>"block","type"=>"danger","msg"=>"The Password Your Using Is Not Correct!"];
        }
      }
      else
      {
             $message=["display"=>"block","type"=>"danger","msg"=>"($email)-This Email Is Not Valid Please Make Correct Or <a href='register.php'>Register Now</a>"];
      }
    }
}
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $message=["display"=>"block","type"=>"danger","msg"=>"Dont Get Any Form Submit!"];
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  </head>
  <body>
    <div style="height: 100vh;" class="container d-flex justify-content-center align-items-center">
        <div style="width: 350px;" class="card p-2">
            <div class="card-header"> <h2 class="text-center">Login</h2></div>
       
        <div class="card-body">
                        <div style="display: <?php echo $message["display"];  ?>;" class="alert alert-<?php echo $message["type"];  ?> mt-2"><?php echo $message["msg"];  ?></div>

            <form  method="post" class="form-group">
                <input value="<?php echo $_SESSION['userEmail'] ; ?>" class="form-control" name="email" required type="email" placeholder="Enter Your Email">
                <input value="<?php echo $_SESSION['userPassword'] ; ?>" class="form-control my-2" name="password" required type="password" placeholder="Enter Your Password">
            <button type="submit" name="loginNow" class="btn btn-primary">Login Now</button>
            <p class="d-flex justify-content-between align-items-center mt-2"><span class="text-secondary">Dont Have Accout?</span><a href="register.php" class="text-primary">Create Now</a></p>
            </form>
        </div>
         </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>