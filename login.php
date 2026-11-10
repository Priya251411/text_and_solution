<?php

session_start();

include "db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";
        $message_type = "error";

    } else {

        $sql = "SELECT id, name, email, password, role, department, phone
                FROM users
                WHERE email = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $message = "Database error: " . $conn->error;
            $message_type = "error";

        } else {

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $user = $result->fetch_assoc();

                if (password_verify($password, $user["password"])) {

                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];
                    $_SESSION["department"] = $user["department"];
                    $_SESSION["phone"] = $user["phone"];

                    header("Location: dashboard.php");
                    exit();

                } else {

                    $message = "Incorrect password.";
                    $message_type = "error";
                }

            } else {

                $message = "No account found with this email.";
                $message_type = "error";
            }

            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Text & Solution | Login</title>

    <link rel="stylesheet" href="style.css">

    <style>

        .login-page {
            min-height: calc(100vh - 90px);
            padding: 60px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                linear-gradient(
                    135deg,
                    #f4f7fb,
                    #e8eef7
                );
        }

        .login-card {
            width: 100%;
            max-width: 470px;
            background: #ffffff;
            padding: 42px;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(20, 40, 80, 0.12);
            border: 1px solid #e5eaf1;
        }

        .login-heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 16px;

            display: flex;
            justify-content: center;
            align-items: center;

            background: linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

            color: white;
            border-radius: 18px;

            font-size: 30px;
        }

        .login-heading h1 {
            margin: 0 0 8px;
            color: #172033;
            font-size: 32px;
        }

        .login-heading p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }

        .login-message {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
        }

        .login-message.error {
            background: #fff0f0;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-group {
            display: flex;
            flex-direction: column;
        }

        .login-group label {
            margin-bottom: 8px;
            color: #273449;
            font-size: 14px;
            font-weight: 600;
        }

        .login-group input {
            padding: 14px 15px;
            border: 1px solid #d7dee8;
            border-radius: 10px;
            background: #fafbfd;
            font-size: 14px;
            outline: none;
            transition: 0.25s ease;
        }

        .login-group input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow:
                0 0 0 4px rgba(37, 99, 235, 0.10);
        }

        .login-button {
            border: none;
            border-radius: 10px;
            padding: 15px;

            background: linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );

            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;

            transition: 0.25s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #718096;
            font-size: 14px;
        }

        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {

            .login-card {
                padding: 30px 20px;
            }

            .login-heading h1 {
                font-size: 27px;
            }
        }

    </style>

</head>

<body>

<header class="navbar">

    <div class="logo">

        <div class="logo-box">
            T
        </div>

        <div>

            <h2>Text & Solution</h2>

            <span>
                Smart Solutions. Better Future.
            </span>

        </div>

    </div>

    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="register.php">
            Register
        </a>

        <a href="login.php" class="active">
            Login
        </a>

    </nav>

</header>


<section class="login-page">

    <div class="login-card">

        <div class="login-heading">

            <div class="login-icon">
                🔐
            </div>

            <h1>
                Welcome Back
            </h1>

            <p>
                Login to your Text & Solution account
            </p>

        </div>


        <?php if (!empty($message)) { ?>

            <div class="login-message <?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php } ?>


        <form
            class="login-form"
            method="POST"
            action="login.php"
        >

            <div class="login-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="login-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-button"
            >
                Login
            </button>

        </form>


        <p class="register-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </p>

    </div>

</section>

</body>

</html>