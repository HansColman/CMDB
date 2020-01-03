<html xmlns="http://www.w3.org/1999/html">
<head>
<title>Wizard scoreboard</title>
<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
<link rel="stylesheet" href="https://raw.githubusercontent.com/daneden/animate.css/master/animate.css" />
<link rel="stylesheet" href="css/bootnavbar.css">
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/647f5b4044.js"></script>
</head>
<body>
<div class="container-fluid">
<h2>Round 2</h2>
<form class="form-horizontal" action="" method="post">
<table class="table table-bordered table-hover">
	<tr>
    	<td rowspan="2" scope="row">round:</td>
    	<th colspan="3" scope="colgroup" class="text-center">Player: 1</th>
    	<th colspan="3" scope="colgroup" class="text-center">Player: 2</th>
    	<th colspan="3" scope="colgroup" class="text-center">Player: 3</th>
	</tr>
	<tr>
        <th scope="col">Required</th>
        <th scope="col">Received</th>
        <th scope="col">Score</th>
        <th scope="col">Required</th>
        <th scope="col">Received</th>
        <th scope="col">Score</th>
        <th scope="col">Required</th>
        <th scope="col">Received</th>
        <th scope="col">Score</th>
  	</tr>
  	<tr>
    	<th scope="row">1:</th>
        <td>0</td>
        <td>0</td>
        <td>20</td>
        <td>0</td>
        <td>0</td>
        <td>20</td>
        <td>0</td>
        <td>0</td>
        <td>20</td>
    </tr>
    <tr>
    	<th scope="row">2:</th>
        <td><input type="text" class="col-1" name="RequiredPlayer1" value="0"></td>
        <td><input type="text" class="col-1" name="ReceivedPlayer1" value="0"></td>
        <td></td>
        <td><input type="text" class="col-1" name="RequiredPlayer2" value="0"></td>
        <td><input type="text" class="col-1" name="ReceivedPlayer2" value="0"></td>
        <td></td>
        <td><input type="text" class="col-1" name="RequiredPlayer3" value="0"></td>
        <td><input type="text" class="col-1" name="ReceivedPlayer3" value="0"></td>
        <td></td>
    </tr>
</table>
<input type="hidden" name="formRound2-submitted" value="1" /><br>
<div class="form-actions">
<button type="submit" class="btn btn-success">Next Round</button>
</div>
</form>
</div>
</body>