<?php 
    session_start();
    include('./assets/config/dbconn.php');

    if(isset($_POST['login']))
    {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            foreach($result as $data)
            {
                $user_id = $data['id'];
                $user_name = $data['fname'].' '.$data['lname'];
                $user_email = $data['email'];
                $role_as = $data['role_as'];
            }

            $_SESSION['auth'] = true;
            //role as      0=user       1=employee     2=admin
            $_SESSION['auth_role'] = $role_as;
            $_SESSION['auth_user'] = 
            [
                'user_id'=>$user_id,
                'user_name'=>$user_name,
                'user_email'=>$user_email,
            ];
            //admin
            if($_SESSION['auth_role'] == '2')
            {
                $_SESSION['message'] = "Welcome to Dashboard";
                header('location: ./admin/index.php');
                exit(0);
             
            //employee    
            }
            elseif($_SESSION['auth_role'] == '1')
            {
                $_SESSION['message'] = "You are Logged In";
                header('location: ./employee/index.php');
                exit(0);

            //user
            }
            elseif($_SESSION['auth_role'] == '0')
            {
                $_SESSION['message'] = "You are Logged In";
                header('location: ./user/index.php');
                exit(0);
            }

        }
        else
        {
            $_SESSION['message'] = "Incorrect Email or Password";
            header('location: login.php');
            exit(0);
        }

    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-----bootstrap----->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-----style----->
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <section class="container">
        <div class="form">
            <?php include('message.php'); ?>
            <div class="form-content">
                <header>Login</header>

                <form action="" method="post">
                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <i class='bx bx-hide eye-icon' ></i>
                    </div>

                    <div class="form-link">
                        <a href="/forgot_password.php" class="forgot-password">Forgot Password?</a>
                    </div>

                    <div class="button">
                        <input type="submit" class="btn" value="Login" name="login">
                    </div>

                    <div class="form-link">
                        <span>Already have an account? <a href="register.php" class="signup-link">Sign Up</a></span>
                    </div>
                </form>
            </div>

            <div class="line"></div>

            <div class="media-option">
                <a href="https://www.facebook.com/" class="facebook-link">
                    <i class='bx bxl-facebook facebook-icon' ></i>
                </a>
                <a href="https://myaccount.google.com/" class="google-link">
                    <i class='bx bxl-google google-icon' ></i>
                </a>
            </div>
        </div>
    </section>

    <script src="./assets/js/script.js"></script>
</body>
</html>