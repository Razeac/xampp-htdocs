<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PODams--Client</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ping.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/main.js"></script>
</head>
<body>
    <?php include("lib/header.php"); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="alert alert-primary text-center" 
                    style="margin-top: 20px;" 
                    role="alert">
                    Student Picture
                </div>
                <img src="img/none.jpg" 
                    class="rounded mx-auto d-block student_picture" 
                    alt="Responsive image"
                    style="width: 100%;">
                <input class="form-control student_id" 
                    type="text" 
                    style="margin-top: 20px;" 
                    placeholder="Student Identity Number"
                    class="text-center">
                <div class="alert alert-success connection_status text-center" 
                    style="margin-top: 20px;" 
                    role="alert">
                </div>
            </div>
            <div class="col-8">
                <div class="alert alert-success text-center" 
                    style="margin-top: 20px;" 
                    role="alert">
                    SERVER CURRENT TIME
                </div>
                <div class="alert alert-primary text-center"
                    role="alert"
                    style="margin-top: 20px; font-size: 56px">
                    <span class="hours"></span>:<span class="minutes"></span>:<span class="seconds"></span>
                    <span class="median"></span>
                </div>
                <div class="alert alert-success text-center" 
                    style="margin-top: 20px;" 
                    role="alert">
                    TIME IN
                </div>
                <div class="alert alert-primary text-center time_in"
                    role="alert"
                    style="margin-top: 20px; font-size: 56px">
                    -------
                </div>
                <div class="alert alert-success text-center" 
                    style="margin-top: 20px;" 
                    role="alert">
                    REMARKS
                </div>
                <div class="alert alert-primary text-center remarks"
                    role="alert"
                    style="margin-top: 20px; font-size: 56px">
                    -------
                </div>
            </div>
            <div class="col-12">
                <div class="alert alert-success text-center" 
                    role="alert">
                    NAME
                </div>
                <div class="alert alert-primary text-center name text-capitalize"
                    role="alert"
                    style="font-size: 45px">
                    -------
                </div>
                <div class="alert alert-success text-center" 
                    role="alert">
                    GRADE AND SECTION
                </div>
                <div class="alert alert-primary text-center grade_section text-capitalize"
                    role="alert"
                    style="font-size: 40px">
                    -------
                </div>
            </div>
        </div>
    </div>
</body>
</html>