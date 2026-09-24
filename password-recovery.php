<?php
    session_start();
    error_reporting(0);
    include('includes/dbconn.php');

    $error = "";
    $msg = "";
    $step = 1; // 1 = Verify identity, 2 = Reset password, 3 = Completed

    // STEP 2: Process Password Change
    if(isset($_POST['change']))
    {
        $newpassword = $_POST['newpassword'];
        $confirmpassword = $_POST['confirmpassword'];
        $studentid = $_SESSION['recovery_studentid'];

        if(empty($newpassword) || empty($confirmpassword)) {
            $error = "Please fill in all password fields.";
            $step = 2;
        } elseif($newpassword !== $confirmpassword) {
            $error = "Passwords do not match. Please re-enter.";
            $step = 2;
        } elseif(empty($studentid)) {
            $error = "Your session expired. Please restart the recovery process.";
            $step = 1;
        } else {
            $hashed = md5($newpassword);
            $con = "UPDATE tblstudents SET Password=:newpassword WHERE id=:studentid";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $hashed, PDO::PARAM_STR);
            $chngpwd1->execute();
            
            unset($_SESSION['recovery_studentid']);
            $msg = "Your password has been successfully updated. You may now sign in.";
            $step = 3;
        }
    }
    // STEP 1: Process Student Verification
    elseif(isset($_POST['submit']))
    {
        $studentid = trim($_POST['studentid']);
        $email = trim($_POST['emailid']);

        if(empty($studentid) || empty($email)) {
            $error = "Please enter both your registered email and Student ID.";
            $step = 1;
        } else {
            $sql = "SELECT id FROM tblstudents WHERE EmailId=:email AND StudentId=:studentid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if($query->rowCount() > 0){
                foreach ($results as $result) {
                    $_SESSION['recovery_studentid'] = $result->id;
                }
                $step = 2;
            } else {
                $error = "Verification failed: No matching student record found with these details.";
                $step = 1;
            }
        }
    }
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Credential Recovery &bull; Student Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">

    <style>
        :root {
            --college-navy: #0f172a;
            --college-blue: #1e3a8a;
            --college-accent: #2563eb;
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
            color: #733434;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

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

        /* RIGHT PANEL: Official Recovery Terminal */
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
            margin-bottom: 28px;
        }

        .step-pill {
            display: inline-block;
            padding: 4px 10px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
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

        /* Inputs */
        .form-group-official {
            margin-bottom: 20px;
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
            font-size: 1.05rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        /* Alerts */
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

        .college-alert-success {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
            padding: 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        /* Action Buttons */
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

        /* Footer & Links */
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
        
        <!-- Left Section: College Authority & Security Info -->
        <div class="academic-panel">
            <div class="academic-header">
                <!-- University Logo -->
                <img src="assets/images/icon/Srm University-logo.png" alt="University Seal" class="univ-logo">
                
                <div>
                    <span class="academic-badge">
                        <i class="ti-shield"></i> Identity &amp; Credential Recovery
                    </span>
                    <h1 class="academic-title">Student Leave Management System</h1>
                    <p class="academic-desc">
                        Official self-service password recovery terminal. Verify your student enrollment record to safely update your institutional account credentials.
                    </p>
                </div>
            </div>

            <!-- Guidance Note -->
            <div>
                <div class="compliance-box">
                    <h6><i class="ti-info-alt"></i> Recovery Requirements</h6>
                    <p>Enter your official university email and Student Registration ID as recorded in your academic profile. If issues persist, contact your department counselor.</p>
                </div>
                
                <div class="academic-footer-text">
                    &copy; <?php echo date('Y'); ?> SRM Institute of Science and Technology. All Rights Reserved.
                </div>
            </div>
        </div>

        <!-- Right Section: Step-by-Step Recovery Terminal -->
        <div class="auth-panel">
            <div class="auth-box">

                <!-- Alert Messages -->
                <?php if(!empty($error)): ?>
                    <div class="college-alert-error" role="alert">
                        <i class="ti-alert"></i>
                        <span><?php echo htmlentities($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if(!empty($msg)): ?>
                    <div class="college-alert-success" role="alert">
                        <i class="ti-check"></i>
                        <span><?php echo htmlentities($msg); ?></span>
                    </div>
                <?php endif; ?>

                <!-- STEP 1: Verify Student Record -->
                <?php if($step == 1): ?>
                    <div class="auth-header">
                        <span class="step-pill">Step 1 of 2</span>
                        <h3>Verify Identity</h3>
                        <p>Provide your registered student details to initiate recovery.</p>
                    </div>

                    <form method="POST" name="signin" autocomplete="off">
                        <!-- Email -->
                        <div class="form-group-official">
                            <label for="studentEmail">Registered University Email</label>
                            <div class="official-input-wrapper">
                                <input type="email" id="studentEmail" name="emailid" placeholder="e.g. student@srmist.edu.in" required autofocus>
                                <i class="ti-email input-icon"></i>
                            </div>
                        </div>

                        <!-- Student ID -->
                        <div class="form-group-official">
                            <label for="studentRegId">Student Roll / Registration ID</label>
                            <div class="official-input-wrapper">
                                <input type="text" id="studentRegId" name="studentid" placeholder="e.g. RA2111003010..." required>
                                <i class="ti-id-badge input-icon"></i>
                            </div>
                        </div>

                        <button id="form_submit" name="submit" type="submit" class="btn-official-signin">
                            <span>Proceed to Verification</span> <i class="ti-arrow-right"></i>
                        </button>

                        <div class="auth-footer">
                            <p class="text-muted mb-0">Remembered your credentials? <a href="index.php">Return to Sign In</a></p>
                        </div>
                    </form>

                <!-- STEP 2: Create New Password -->
                <?php elseif($step == 2): ?>
                    <div class="auth-header">
                        <span class="step-pill">Step 2 of 2</span>
                        <h3>Set New Password</h3>
                        <p>Student verified. Please create and confirm your new password.</p>
                    </div>

                    <form method="POST" name="updatepwd" autocomplete="off">
                        <!-- New Password -->
                        <div class="form-group-official">
                            <label for="newPassword">Enter New Password</label>
                            <div class="official-input-wrapper">
                                <input type="password" id="newPassword" name="newpassword" placeholder="Minimum 6 characters" required autofocus>
                                <button type="button" class="input-icon toggle-btn" onclick="toggleVisibility('newPassword', 'toggleNewIcon')">
                                    <i class="ti-eye" id="toggleNewIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group-official">
                            <label for="confirmPassword">Confirm New Password</label>
                            <div class="official-input-wrapper">
                                <input type="password" id="confirmPassword" name="confirmpassword" placeholder="Re-type new password" required>
                                <button type="button" class="input-icon toggle-btn" onclick="toggleVisibility('confirmPassword', 'toggleConfirmIcon')">
                                    <i class="ti-eye" id="toggleConfirmIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button id="form_submit" name="change" type="submit" class="btn-official-signin">
                            <i class="ti-check"></i> <span>Update Password</span>
                        </button>

                        <div class="auth-footer">
                            <a href="password-recovery.php"><i class="ti-reload"></i> Cancel &amp; Restart</a>
                        </div>
                    </form>

                <!-- STEP 3: Successful Update Confirmation -->
                <?php elseif($step == 3): ?>
                    <div class="text-center pt-3">
                        <div style="width: 68px; height: 68px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="ti-check" style="font-size: 2rem; color: #16a34a;"></i>
                        </div>
                        <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--college-navy);">Password Changed</h3>
                        <p class="text-muted" style="font-size: 0.95rem; margin-bottom: 28px;">
                            Your credentials have been securely updated in the student registry.
                        </p>
                        <a href="index.php" class="btn-official-signin" style="text-decoration: none;">
                            <i class="ti-arrow-right"></i> Proceed to Student Login
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Password Visibility Toggle Function -->
    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-lock');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-lock');
                icon.classList.add('ti-eye');
            }
        }
    </script>
</body>

</html>
