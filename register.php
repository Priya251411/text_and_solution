```php
<?php
include "db.php";

$message = "";
$message_type = "";

$name = "";
$email = "";
$phone = "";
$role = "";
$department = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "";
    $department = trim($_POST["department"] ?? "");

    if (
        $name === "" || $email === "" || $phone === "" ||
        $password === "" || $role === "" || $department === ""
    ) {
        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } elseif (!in_array($role, ["admin", "employee"], true)) {
        $message = "Please select a valid role.";
        $message_type = "error";

    } else {
        // Check duplicate email
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        if (!$check) {
            $message = "Database error: " . $conn->error;
            $message_type = "error";
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "This email is already registered.";
                $message_type = "error";
            } else {
                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                // Every newly registered account awaits approval.
                $status = "pending";

                $insert = $conn->prepare(
                    "INSERT INTO users
                    (name, email, password, role, department, phone, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)"
                );

                if (!$insert) {
                    $message = "Database error: " . $conn->error;
                    $message_type = "error";
                } else {
                    $insert->bind_param(
                        "sssssss",
                        $name,
                        $email,
                        $hashed_password,
                        $role,
                        $department,
                        $phone,
                        $status
                    );

                    if ($insert->execute()) {
                        $message = "Registration successful! Your account is waiting for admin approval.";
                        $message_type = "success";

                        $name = "";
                        $email = "";
                        $phone = "";
                        $role = "";
                        $department = "";
                    } else {
                        $message = "Registration failed: " . $insert->error;
                        $message_type = "error";
                    }

                    $insert->close();
                }
            }

            $check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text &amp; Solution | Register</title>
    <link rel="stylesheet" href="style.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #172033;
        }

        .register-page {
            min-height: calc(100vh - 90px);
            padding: 50px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f4f7fb, #e8eef7);
        }

        .register-card {
            width: 100%;
            max-width: 850px;
            padding: 40px;
            background: white;
            border: 1px solid #e5eaf1;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(20, 40, 80, .12);
        }

        .register-heading {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 16px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #5b45e8);
            color: white;
            font-size: 30px;
        }

        .register-heading h1 {
            margin: 0 0 9px;
            font-size: 32px;
        }

        .register-heading p {
            color: #718096;
            margin: 0;
        }

        .message {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 22px;
            text-align: center;
            line-height: 1.5;
        }

        .message.success {
            color: #166534;
            background: #e9f9ef;
            border: 1px solid #b7ebc6;
        }

        .message.error {
            color: #b91c1c;
            background: #fff0f0;
            border: 1px solid #fecaca;
        }

        .register-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #d7dee8;
            border-radius: 10px;
            background: #fafbfd;
            font-size: 15px;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .register-button {
            grid-column: 1 / -1;
            padding: 15px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .register-button:hover {
            background: #1d4ed8;
        }

        .login-link {
            margin: 25px 0 0;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }

        .login-link a {
            color: #2563eb;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 650px) {
            .register-card { padding: 28px 20px; }
            .register-form { grid-template-columns: 1fr; }
            .form-group.full,
            .register-button { grid-column: auto; }
            .register-heading h1 { font-size: 26px; }
        }
    </style>
</head>

<body>
<header class="navbar">
    <div class="logo">
        <div class="logo-box">T</div>
        <div>
            <h2>Text &amp; Solution</h2>
            <span>Smart Solutions. Better Future.</span>
        </div>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="register.php" class="active">Register</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<section class="register-page">
    <div class="register-card">
        <div class="register-heading">
            <div class="register-icon">👤</div>
            <h1>Create Your Account</h1>
            <p>Register as an Admin or Employee</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message <?php echo htmlspecialchars($message_type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form class="register-form" method="POST" action="register.php">
            <div class="form-group full">
                <label for="name">Full Name</label>
                <input
                    type="text" id="name" name="name"
                    placeholder="Enter your full name"
                    value="<?php echo htmlspecialchars($name); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email" id="email" name="email"
                    placeholder="example@gmail.com"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                    type="tel" id="phone" name="phone"
                    placeholder="Enter phone number"
                    value="<?php echo htmlspecialchars($phone); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password" id="password" name="password"
                    placeholder="Minimum 6 characters"
                    minlength="6" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="employee"
                        <?php echo $role === "employee" ? "selected" : ""; ?>>
                        Employee
                    </option>
                    <option value="admin"
                        <?php echo $role === "admin" ? "selected" : ""; ?>>
                        Admin
                    </option>
                </select>
            </div>

            <div class="form-group full">
                <label for="department">Department</label>
                <input
                    type="text" id="department" name="department"
                    placeholder="Example: IT, HR, Finance"
                    value="<?php echo htmlspecialchars($department); ?>"
                    required>
            </div>

            <button class="register-button" type="submit">
                Create Account
            </button>
        </form>

        <p class="login-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </div>
</section>
</body>
</html>
```