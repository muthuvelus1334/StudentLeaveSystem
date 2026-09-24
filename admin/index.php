<?php
    session_start();
    error_reporting(0);
    include('../includes/dbconn.php');

    $error = "";
    if(isset($_POST['signin'])){
        $uname = trim($_POST['username']);
        $password = md5($_POST['password']);
        $sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            $_SESSION['alogin'] = $uname;
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        } else {
            $error = "Authentication failed. Invalid administrator credentials.";
        }
    }
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Administrative Portal &bull; Student Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="../assets/images/icon/favicon.ico">
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">

    <style>
        :root {
            --college-navy: #0f172a;
            --college-blue: #1e3a8a;
            --college-accent: #2563eb;
            --college-gold: #b45309;
            --slate-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--slate-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .portal-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            background: #ffffff;
        }

        /* LEFT PANEL: Academic Identity */
        .academic-panel {
            flex: 1.1;
            background: linear-gradient(145deg, #0b1329 0%, #1e293b 100%);
            color: #ffffff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Subtle university geometric pattern */
        .academic-panel::before {
            content: "";
            position: absolute;
            top: -20%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(37, 99, 235, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .academic-header .univ-logo {
            max-height: 60px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            margin-bottom: 24px;
        }

        .academic-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            color: #93c5fd;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .academic-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .academic-desc {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            max-width: 480px;
            margin-bottom: 36px;
        }

        .compliance-box {
            background: rgba(255, 255, 255, 0.05);
            border-left: 3px solid #38bdf8;
            padding: 16px 20px;
            border-radius: 0 8px 8px 0;
            margin-top: auto;
        }

        .compliance-box h6 {
            color: #e2e8f0;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .compliance-box p {
            color: #94a3b8;
            font-size: 0.82rem;
            margin: 0;
            line-height: 1.45;
        }

        .academic-footer-text {
            margin-top: 30px;
            color: #64748b;
            font-size: 0.8rem;
        }

        /* RIGHT PANEL: Official Login Terminal */
        .auth-panel {
            flex: 0.9;
            background: #ffffff;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-box {
            width: 100%;
            max-width: 420px;
        }

        .auth-header {
            margin-bottom: 32px;
        }

        .auth-header h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--college-navy);
            margin-bottom: 6px;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin: 0;
        }

        /* Input Controls */
        .form-group-official {
            margin-bottom: 22px;
        }

        .form-group-official label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .official-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .official-input-wrapper input {
            width: 100%;
            height: 48px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 0 42px 0 14px;
            font-size: 0.95rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .official-input-wrapper input:focus {
            background: #ffffff;
            border-color: var(--college-accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }

        .official-input-wrapper .input-icon {
            position: absolute;
            right: 14px;
            color: #94a3b8;
            font-size: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        /* Alert Notification */
        .college-alert-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        /* Official Button */
        .btn-official-signin {
            width: 100%;
            height: 48px;
            background: var(--college-blue);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
            margin-top: 10px;
        }

        .btn-official-signin:hover {
            background: #172554;
        }

        .btn-official-signin:active {
            transform: scale(0.99);
        }

        /* Navigation Links & Footer */
        .auth-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: center;
            font-size: 0.86rem;
        }

        .auth-footer a {
            color: var(--college-accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            .portal-container {
                flex-direction: column;
            }
            .academic-panel {
                padding: 40px 24px;
            }
            .auth-panel {
                padding: 40px 24px;
            }
            .academic-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <div class="portal-container">
        
        <!-- Left Section: College Authority & Branding -->
        <div class="academic-panel">
            <div class="academic-header">
                <!-- University Logo -->
                <img src="../assets/images/icon/Srm University-logo.png" alt="University Seal" class="univ-logo">
                
                <div>
                    <span class="academic-badge">
                        <i class="ti-bookmark-alt"></i> Official Academic Portal
                    </span>
                    <h1 class="academic-title">Student Leave Management System</h1>
                    <p class="academic-desc">
                        Official administrative terminal for Department Heads, Faculty Mentors, and the Office of the Registrar to review, sanction, and record student leave applications.
                    </p>
                </div>
            </div>

            <!-- Security & Compliance Notice -->
            <div>
                <div class="compliance-box">
                    <h6><i class="ti-lock"></i> Restricted Faculty &amp; Staff Access</h6>
                    <p>This administrative portal is monitored under Institutional IT Security Guidelines. Unauthorized attempts are logged.</p>
                </div>
                
                <div class="academic-footer-text">
                    &copy; <?php echo date('Y'); ?> SRM Institute of Science and Technology. All Rights Reserved.
                </div>
            </div>
        </div>

        <!-- Right Section: Secure Sign In -->
        <div class="auth-panel">
            <div class="auth-box">
                <div class="auth-header">
                    <h3>Administrative Sign In</h3>
                    <p>Enter your institutional staff credentials to continue.</p>
                </div>

                <!-- Error Notification -->
                <?php if(!empty($error)): ?>
                    <div class="college-alert-error" role="alert">
                        <i class="ti-alert"></i>
                        <span><?php echo htmlentities($error); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form name="signin" method="POST" autocomplete="off">
                    
                    <!-- Username -->
                    <div class="form-group-official">
                        <label for="adminUsername">Administrator Username / Employee ID</label>
                        <div class="official-input-wrapper">
                            <input type="text" id="adminUsername" name="username" placeholder="e.g. admin or faculty ID" required autofocus>
                            <i class="ti-user input-icon"></i>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group-official">
                        <label for="adminPassword">Security Password</label>
                        <div class="official-input-wrapper">
                            <input type="password" id="adminPassword" name="password" placeholder="Enter password" required>
                            <button type="button" class="input-icon toggle-btn" id="togglePasswordBtn" title="Show/Hide Password">
                                <i class="ti-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sign In Button -->
                    <button id="form_submit" type="submit" name="signin" class="btn-official-signin">
                        <i class="ti-key"></i> Authenticate &amp; Log In
                    </button>

                    <!-- Auxiliary Links -->
                    <div class="auth-footer">
                        <div>
                            <a href="../index.php">
                                <i class="ti-arrow-left"></i> Return to Student Portal Home
                            </a>
                        </div>
                        <span class="text-muted" style="font-size: 0.78rem;">
                            Need assistance? Contact Campus IT Helpdesk
                        </span>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="../assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Password Visibility Toggle -->
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('adminPassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('ti-eye');
                toggleIcon.classList.add('ti-lock');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('ti-lock');
                toggleIcon.classList.add('ti-eye');
            }
        });
    </script>
</body>

</html>
