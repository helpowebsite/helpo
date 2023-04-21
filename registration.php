<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];

           
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            
            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ss",$email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You have registered successfully</div>";
            }else{
                die("Something went wrong");
            }
           }
          

        }
        ?>

    <div class="wrapper">

    <div class="title-text">
        <div class="title login">
            Register Form
        </div>
    </div>

        <div class="form-inner">
            <div class="card-body">
                <form action="registration.php" method="post">
                    <div class="field">
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email:">
                        </div>   
                    </div>
                    <div class="field">
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password:">
                        </div>
                    </div>
                    <div class="field">
                        <div class="form-group">
                            <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
                        </div>
                    </div>
                    </div>

                    <div class="login-link">
                        Already registered? <a href="login.php">Log in</a>
                    </div>
                    
                    <div class="field-btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Register" name="submit">
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>