<?php ob_start();?>
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
  		<div class="container-fluid">
        	<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                	<span class="sr-only">Toggle navigation</span>
                	<span class="icon-bar"></span>
                	<span class="icon-bar"></span>
                	<span class="icon-bar"></span>
              	</button>
      			<a class="navbar-brand" href="#">CMDB</a>
    		</div>
			<div class="collapse navbar-collapse">
    			<ul class="nav navbar-nav">
        			<li><a href="main.php">Home</a></li>
        			<?php foreach ($FirstMenu as $row){?>
                    <li><!-- 1 -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="<?php print $row["label"];?>"><?php print $row["label"];?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                        <?php $SecondLevels = $this->accessService->getSecondLevel($row["Menu_id"]);         
                        foreach($SecondLevels as $SecondLevel){?>
                            <li class="dropdown-submenu"><!-- 2 -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="<?php print $SecondLevel["label"];?>2"><?php print $SecondLevel["label"];?> <b class="caret"></b></a>
                                <?php $ThirdLevels = $this->accessService->getThirdLevel($Level,$SecondLevel["Menu_id"]);
                                foreach($ThirdLevels as $ThirdLevel){?>
                                    <ul class="dropdown-menu"><!-- 3 -->
                                       <li><a href="<?php print $ThirdLevel["link_url"];?>" id="<?php print $ThirdLevel["label"];?>"><?php print $ThirdLevel["label"];?></a></li>
                                    </ul><?php }?>
                            </li><?php }?>
                        </ul>
                    </li><?php }?>
            	</ul>
            	<ul class="nav navbar-nav navbar-right">
            		<li><a href="logout.php" class="btn btn-danger"><span style="color:white;" class="glyphicon glyphicon-log-out"></span><span style="color:white;"> Log out</span></a></li>
            	</ul>
			</div><!-- /.navbar-collapse -->
  		</div><!-- /.container-fluid -->
	</div>
</div>
<div class="container">