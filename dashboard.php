```php
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION["name"] ?? "User";
$email = $_SESSION["email"] ?? "";
$role = $_SESSION["role"] ?? "employee";
$department = $_SESSION["department"] ?? "";
$phone = $_SESSION["phone"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Text &amp; Solution</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .navbar {
            background: #0f172a;
            color: white;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 23px;
            font-weight: bold;
        }

        .logo span {
            color: #38bdf8;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 7px;
            font-size: 14px;
        }

        .admin-link {
            background: #2563eb;
        }

        .logout-link {
            background: #dc2626;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .welcome {
            background: linear-gradient(135deg, #1d4ed8, #0f172a);
            color: white;
            padding: 32px;
            border-radius: 16px;
            margin-bottom: 25px;
        }

        .welcome h1 {
            font-size: 29px;
            margin-bottom: 10px;
        }

        .welcome p {
            line-height: 1.7;
            color: #dbeafe;
        }

        .role-badge {
            display: inline-block;
            margin-top: 15px;
            padding: 7px 13px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .details-card {
            background: white;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.06);
        }

        .details-card h2 {
            margin-bottom: 22px;
            font-size: 22px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 16px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-weight: bold;
        }

        .detail-value {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .admin-card {
            background: white;
            padding: 25px;
            margin-top: 25px;
            border-radius: 14px;
            border: 1px solid #bfdbfe;
        }

        .admin-card h2 {
            margin-bottom: 10px;
            color: #1d4ed8;
        }

        .admin-card p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .primary-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 25px 10px;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 18px 5%;
            }

            .container {
                margin: 25px auto;
            }

            .welcome {
                padding: 24px;
            }

            .welcome h1 {
                font-size: 24px;
            }

            .details-card {
                padding: 20px;
            }

            .detail-row {
                flex-direction: column;
                gap: 7px;
            }

            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">Text <span>&amp;</span> Solution</div>

        <div class="nav-links">
            <?php if ($role === "admin"): ?>
                <a class="admin-link" href="admin_approval.php">
                    Employee Approvals
                </a>
            <?php endif; ?>

            <a class="logout-link" href="logout.php">Logout</a>
        </div>
    </nav>

    <main class="container">

        <section class="welcome">
            <h1>Welcome, <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>!</h1>

            <p>
                You have successfully logged in to Text &amp; Solution.
                Manage your account from your dashboard.
            </p>

            <span class="role-badge">
                <?= htmlspecialchars($role, ENT_QUOTES, "UTF-8") ?> Account
            </span>
        </section>

        <section class="details-card">
            <h2>Account Details</h2>

            <div class="detail-row">
                <span class="detail-label">Full Name</span>
                <span class="detail-value">
                    <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email Address</span>
                <span class="detail-value">
                    <?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone Number</span>
                <span class="detail-value">
                    <?= htmlspecialchars($phone, ENT_QUOTES, "UTF-8") ?: "Not provided" ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Department</span>
                <span class="detail-value">
                    <?= htmlspecialchars($department, ENT_QUOTES, "UTF-8") ?: "Not assigned" ?>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account Type</span>
                <span class="detail-value">
                    <?= htmlspecialchars(ucfirst($role), ENT_QUOTES, "UTF-8") ?>
                </span>
            </div>
        </section>

        <?php if ($role === "admin"): ?>
            <section class="admin-card">
                <h2>Admin Control Panel</h2>

                <p>
                    Review employee registration details and approve or
                    reject pending employee accounts.
                </p>

                <a class="primary-btn" href="admin_approval.php">
                    Manage Employee Approvals
                </a>
            </section>
        <?php else: ?>
            <section class="admin-card">
                <h2>Employee Dashboard</h2>

                <p>
                    This is your employee account dashboard. If you need
                    to update your registered information, contact your
                    administrator.
                </p>
            </section>
        <?php endif; ?>

    </main>

    <footer>
        &copy; <?= date("Y") ?> Text &amp; Solution. All rights reserved.
    </footer>

</body>
</html>
```