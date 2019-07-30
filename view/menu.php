<?php ob_start();?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" id="main_navbar">
	<a class="navbar-brand" href="#">CMDB</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    	<ul class="navbar-nav mr-auto">
    		<li class="nav-item active">
    			<a href="main.php">Home <span class="sr-only">(current)</span></a>
    		</li>
    		<?php foreach ($FirstMenu as $row){?>
            <li class="nav-item dropdown"><!-- 1 -->
                <a class="nav-link dropdown-toggle" href="#" id="<?php print $row["label"];?>" 
                	role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                	<?php print $row["label"];?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="<?php print $row["label"];?>">
                    <?php $SecondLevels = $this->accessService->getSecondLevel($row["Menu_id"]);         
                    foreach($SecondLevels as $SecondLevel){?>
                        <li class="nav-item dropdown"><!-- 2 -->
                            <a class="dropdown-item dropdown-toggle" href="#" id="<?php print $SecondLevel["label"];?>2" 
                            	role="button" data-toggle="dropdown"
	                            aria-haspopup="true" aria-expanded="false">
	                        <?php print $SecondLevel["label"];?></a>
                            <?php $ThirdLevels = $this->accessService->getThirdLevel($Level,$SecondLevel["Menu_id"]);
                            foreach($ThirdLevels as $ThirdLevel){?>
                                <ul class="dropdown-menu" aria-labelledby="<?php print $SecondLevel["label"];?>2"><!-- 3 -->
                               		<li>
                               			<a class="dropdown-item" href="<?php print $ThirdLevel["link_url"];?>" id="<?php print $ThirdLevel["label"];?>">
                                   		<?php print $ThirdLevel["label"];?>
                                   		</a>
                               		</li>
                                </ul><?php }?>
                        </li><?php }?>
            	</ul>
            </li>
           	<?php }?>
		</ul>
		<form class="form-inline my-2 my-lg-0" action="Logout.php">
			<button class="btn btn-danger">Log out</button>
		</form>
	</div>
</nav>
<div class="container-fluid">