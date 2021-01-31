<?php
session_start ();

if (! (isset ( $_SESSION ['login'] ))) {
	
	header ( 'location:../index.php' );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>PODams--Client</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link href="../bower_components/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="../bower_components/metisMenu/dist/metisMenu.min.css"
	rel="stylesheet">
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
<link href="../bower_components/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ping.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/main.js"></script>
</head>
<!-- <head>
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
</head> -->
<body>
<form method="post" >
	<div id="wrapper">
	<!--left !-->
    <?php include('leftbar.php')?>;
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
            </div>
        </div>
    </div>
</body>
</html>