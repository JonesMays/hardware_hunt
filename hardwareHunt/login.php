<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// var_dump output variables ot the screen
// request contains all submitted form information
// var_dump($_REQUEST);

// echo "<br><br>hello " . $_REQUEST["fullname"];//

//step 1 connect to database

$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

// inlcude "../anvariables.php";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

// if I can find an error number then stop because there was a problem
if($mysql->connect_errno) { //if error
    echo "db connection error : " . $mysql->connect_error; //tell me there was an erro
    exit(); //stop running page
} else {
    // echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1e;
            color: #ffffff;
            align-items: center;
        }

        .form-container {
            background-color: #2c2c2e;
            padding: 2em;
            border-radius: 10px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            margin: 2em auto;
        }

        .toggle-bar {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1em;
            cursor: pointer;
            margin-bottom: 40px;
        }

        .toggle-bar span {
            font-size: 1.2em;
            color: #ffaa33;
            padding: 0.5em 1em;
            border-radius: 5px;
            width: 50%;
            text-align: center;
            align-items: center;
        }

        .toggle-bar span.active {
            background-color: #ffaa33;
            color: #1c1c1e;
        }

        .form-group {
            margin-bottom: 1em;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 0.75em;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #3a3a3c;
            color: #ffffff;
        }

        .form-actions {
            text-align: center;
        }

        .form-actions button {
            background-color: #ffaa33;
            border: none;
            padding: 0.75em 1.5em;
            border-radius: 5px;
            color: #1c1c1e;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            margin-top: 10px;
        }

        .form-actions button:hover {
            background-color: #e5992e;
        }

    </style>
</head>
<body>

<iframe src="NewPages/navbar.html" style="border: none; width: 100%; height: auto;" id="navbar"></iframe>
<!-- di i need a form? -->

<?php
// Check if a POST request was made
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //login php
    if ($_POST["form_type"] === "login") {
        $sql = "SELECT * FROM users WHERE 
            users.email = '" . $_POST["email"] . "' AND 
            users.password = '" . $_POST["password"] . "'";

        $results = $mysql->query($sql);
        if (!$results) {
            echo "SQL error: " . $mysql->error;
            exit();
        }
        if ($results->num_rows > 0) {
            $user = $results->fetch_assoc();
            $_SESSION["user_admin"] = $user['admin'];
            $_SESSION["user_user_id"] = $user['user_id'];
            $_SESSION["user_email"] = $user['email'];
            $_SESSION["user_password"] = $user['password'];
            $_SESSION["user_first_name"] = $user['first_name'];
            $_SESSION["user_last_name"] = $user['last_name'];
            echo "<script>alert('Logged In');
                 window.location.href = 'search.php';
                </script>";
        } else {
            echo "<script>alert('Invalid Email or Password');</script>";
        }
    }

    //signup php
    if ($_POST["form_type"] === "signup") {
        $sql = "
            INSERT INTO users
            (email, password, admin, first_name, last_name)
            VALUES 
            (
                '{$_POST['signup_email']}',
                '{$_POST['signup_password']}',
                FALSE,
                '{$_POST['first_name']}',
                '{$_POST['last_name']}'
            )
        ";
        $results = $mysql->query($sql);

        if (!$results) {
            echo "Error: " . $sql . "<br>" . $mysql->error;
            echo "<hr>";
            exit();
        }

        $to = $_POST['signup_email'];
        $subject = "Hardware Hunt Account Created";
        $message = "Hi " . $_POST["first_name"] . ",\r\n";
        $message .= "You have created an account for Hardware Hunt. Have fun making!";
        $response = mail($to, $subject, $message);

        echo "<script>alert('Account created successfully!');</script>";
    }
}
?>
<main>
    <div class="form-container">
        <div class="toggle-bar">
            <span id="login-tab" class="active">Log In</span>
            <span id="signup-tab">Sign Up</span>
        </div>
        <form id="login-form" method = "POST">
            <input type="hidden" name="form_type" value="login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-actions">
                <button type="submit">Log In</button>
            </div>
        </form>
        <form id="signup-form" style="display: none;" method = "POST">
            <input type="hidden" name="form_type" value="signup">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="signup-email">Email</label>
                <input type="email" id="signup-email" name="signup_email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name="signup_password" placeholder="Create a password" required>
            </div>
            <div class="form-actions">
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>
</main>

<script>
    const loginTab = document.getElementById('login-tab');
    const signupTab = document.getElementById('signup-tab');
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');

    loginTab.addEventListener('click', () => {
        loginTab.classList.add('active');
        signupTab.classList.remove('active');
        loginForm.style.display = 'block';
        signupForm.style.display = 'none';
    });

    signupTab.addEventListener('click', () => {
        signupTab.classList.add('active');
        loginTab.classList.remove('active');
        signupForm.style.display = 'block';
        loginForm.style.display = 'none';
    });
</script>
</body>
</html>
