<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Test CMDB</title>
    <!-- Bootstrap -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/navbar.css" rel="stylesheet">
		<link href="css/font-awesome.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
       <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
		
</head>
<body>
<style>
    body {
        padding-top: 50px;
    }
    .navbar-template {
        padding: 40px 15px;
    }
</style>
    <?php
    session_start();
    $_SESSION["WhoName"] = "Root";
    $_SESSION["Level"]= 9;
    require_once 'controller/MenuController.php';
    $controller = new MenuController();
    $controller->handleRequest();
    ?>