<?php 
// Start the output buffering:
ob_start();

// Check for the $page_title:
if (!isset($page_title)) {
	$page_title = 'DAW Yellowspine Book Collection';
}
?>

<!doctype html>

<html lang="en">
    <head>
        <!--Page last updated:11/08/2015-->
        
        <!--Character set-->
        <meta charset="utf-8">
        <!--Description-->
        <meta name="description" content="DAW Yellowspine Book Collection">
        <!--Author-->
        <meta name="author" content="John Baker">
        <!--Set viewport for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        <!--Page title-->
        <title>DAW Yellowspine - <?php echo $page_title ?></title>
        
        <!--Web fonts-->
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        
        <!-- Latest compiled and minified Bootstrap CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        
        <!-- CSS Override rules-->
        <link rel="stylesheet" href="css/overrides.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <!-- Latest compiled Bootstrap JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
        <!-- Angular JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.js" type="text/javascript"></script>
        
        <!-- App Module -->
        <script src="js/app.js" type="text/javascript"></script>
        
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
</head>

<body ng-app="yellowSpine">