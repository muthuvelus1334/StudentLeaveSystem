<?php
    session_start();
    error_reporting(0);
    include('../includes/dbconn.php');
    if(strlen($_SESSION['studentlogin'])==0)
    {   
        header('location:../index.php');
    } else {
        if(isset($_POST['apply']))
        {
            $studentid = $_SESSION['eid'];
            $leavetype = trim($_POST['leavetype'] ?? '');
            $fromdate = trim($_POST['fromdate'] ?? '');  
            $todate = trim($_POST['todate'] ?? '');
            $description = trim($_POST['description'] ?? '');  
            $status = 0;
            $isread = 0;
            $currentDate = date('Y-m-d');

            // Server-side validation
            if(empty($leavetype) || empty($fromdate) || empty($todate) || empty($description)) {
                $error = "All fields are mandatory. Please fill in all the details!";
            } elseif($fromdate < $currentDate) {
                $error = "Starting Date cannot be a past date!";
            } elseif($todate < $fromdate) {
                $error = "Leave Up To Date cannot be earlier than Starting Date!";
            } else {
                $sql = "INSERT INTO tblleaves(LeaveType,FromDate,ToDate,Description,Status,IsRead,studentid) VALUES(:leavetype,:fromdate,:todate,:description,:status,:isread,:studentid)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
                $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
                $query->bindParam(':todate', $todate, PDO::PARAM_STR);
                $query->bindParam(':description', $description, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':isread', $isread, PDO::PARAM_STR);
                $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();

                if($lastInsertId) {
                    $msg = "Your leave application has been applied, Thank You.";
                } else {
                    $error = "Sorry, could not process this time. Please try again later.";
                }
            }
        }
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Student Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="../assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/metisMenu.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="../assets/css/typography.css">
    <link rel="stylesheet" href="../assets/css/default-css.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="../assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="leave.php"><img src="../assets/images/icon/Srm University-logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="leave.php" aria-expanded="true"><i class="ti-user"></i><span>Apply Leave</span></a>
                            </li>
                            <li class="#">
                                <a href="leave-history.php" aria-expanded="true"><i class="ti-agenda"></i><span>View My Leave History</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 clearfix">
                        <ul class="notification-area pull-right">
                            <li id="full-view"><i class="ti-fullscreen"></i></li>
                            <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- header area end -->
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Apply For Leave Days</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><span>Leave Form</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <?php include '../includes/employee-profile-section.php'?>
                    </div>
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-lg-6 col-ml-12">
                        <div class="row">
                            <div class="col-12 mt-5">
                            <?php if($error){?><div class="alert alert-danger alert-dismissible fade show"><strong>Info: </strong><?php echo htmlentities($error); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                             </div><?php } 
                                 else if($msg){?><div class="alert alert-success alert-dismissible fade show"><strong>Info: </strong><?php echo htmlentities($msg); ?> 
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                 </div><?php }?>
                                <div class="card">
                                <form name="addemp" method="POST">

                                    <div class="card-body">
                                        <h4 class="header-title">Student Leave Form</h4>
                                        <p class="text-muted font-14 mb-4">Please fill up all required fields (<span class="text-danger">*</span>) below.</p>

                                        <!-- Starting Date: Defaults to Today, Past Dates Blocked -->
                                        <div class="form-group">
                                            <label for="fromdate-input" class="col-form-label">Starting Date <span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" id="fromdate-input" name="fromdate" 
                                                   value="<?php echo date('Y-m-d'); ?>" 
                                                   min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

                                        <!-- Leave Up To Date: Only Allows Future / Selected Dates -->
                                        <div class="form-group">
                                            <label for="todate-input" class="col-form-label">Leave Up To Date <span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" id="todate-input" name="todate" 
                                                   min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

                                        <!-- Leave Type -->
                                        <div class="form-group">
                                            <label class="col-form-label">Your Leave Type <span class="text-danger">*</span></label>
                                            <select class="custom-select" name="leavetype" autocomplete="off" required>
                                                <option value="">Click here to select any ...</option>
                                                <?php 
                                                    $sql = "SELECT LeaveType from tblleavetype";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if($query->rowCount() > 0) {
                                                        foreach($results as $result) { ?> 
                                                            <option value="<?php echo htmlentities($result->LeaveType);?>"><?php echo htmlentities($result->LeaveType);?></option>
                                                <?php   }
                                                    } 
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group">
                                            <label for="description-input" class="col-form-label">Describe Your Conditions <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description" id="description-input" rows="5" required></textarea>
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-3">
                                            <button class="btn btn-primary" name="apply" id="apply" type="submit">SUBMIT</button>
                                            <a href="javascript:history.back()" class="btn btn-secondary ml-2">
                                                <i class="fa fa-arrow-left"></i> Back
                                            </a>
                                        </div>
                                        
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <?php include '../includes/footer.php' ?>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->

    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
    </div>
    <!-- offset area end -->

    <!-- jquery latest version -->
    <script src="../assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/jquery.slimscroll.min.js"></script>
    <script src="../assets/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="../assets/js/plugins.js"></script>
    <script src="../assets/js/scripts.js"></script>

    <!-- Dynamic Date Restriction Script -->
    <script>
        const fromDateInput = document.getElementById('fromdate-input');
        const toDateInput = document.getElementById('todate-input');

        // Automatically set the minimum 'Leave Up To Date' when the page loads
        if (fromDateInput.value) {
            toDateInput.min = fromDateInput.value;
        }

        // Dynamically update the minimum date whenever Starting Date changes
        fromDateInput.addEventListener('change', function() {
            const selectedStartDate = this.value;
            toDateInput.min = selectedStartDate;
            
            // If the current 'Leave Up To Date' is before the new Start Date, reset it
            if (toDateInput.value && toDateInput.value < selectedStartDate) {
                toDateInput.value = selectedStartDate;
            }
        });
    </script>
</body>

</html>
<?php } ?>
