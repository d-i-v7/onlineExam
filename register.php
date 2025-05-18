<?php 
// Calling Connection file
require("includes/conn.php");
// Default Message
  $message=["display"=>"none","type"=>"","msg"=>""];

if(isset($_POST['registerNow']))
{
    // Extract All Post Methods
    extract($_POST);
    // Form Validation
    if(empty($name) || empty($email) || empty($password))
    {
     $message=["display"=>"block","type"=>"danger","msg"=>"All Fields Are Reuqired!"];
    }
    else
    {
        // Cheack If User Email Is Taken Email
        $select = mysqli_query($conn , "SELECT * FROM users WHERE email='$email'");
        if($select && mysqli_num_rows($select)>0)
        {
        $message=["display"=>"block","type"=>"danger","msg"=>"($email)-This Email Is Already Taken!"];
        }
        else
        {
            // Hashed Password
            $hashPassword = password_hash($password,PASSWORD_DEFAULT);
            // Now Register The User
            $insert = mysqli_query($conn,"INSERT INTO users (`name`,`email`,`password`)VALUES('$name','$email','$hashPassword')");
            if($insert)
            {
                  $message=["display"=>"block","type"=>"success","msg"=>"You have successfully registered. Please wait while we review your request. A confirmation email will be sent to ($email) once your registration is approved."];
            }
            else
            {
              $message=["display"=>"block","type"=>"danger","Something Went Wrong Wait Few Hours!"];
            }
        }
    }
}
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $message=["display"=>"block","type"=>"danger","msg"=>"Dont Submit Any Form !"];
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
            <div class="card-header"> <h2 class="text-center">Create An Account</h2></div>
       
        <div class="card-body">
                        <div style="display: <?php echo $message["display"];  ?>;" class="alert alert-<?php echo $message["type"];  ?> mt-2"><?php echo $message["msg"];  ?></div>
            <form  method="post" class="form-group">
                <input class="form-control" name="name" required type="text" placeholder="Enter Your Name">
                <input class="form-control my-2" name="email" required type="email" placeholder="Enter Your Email">
                <input class="form-control my-2" name="password" required type="password" placeholder="Enter Your Password">
            <button name="registerNow" type="submit" class="btn btn-primary">Register Now</button>
            <p class="d-flex justify-content-between align-items-center mt-2"><span class="text-secondary">Already Have Accout?</span><a href="index.php" class="text-primary">Login Now</a></p>
            </form>
        </div>
         </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>