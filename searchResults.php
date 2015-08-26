<?php include 'webkitConfig.php';?>

<!DOCTYPE html>
<html>
<head>
<title>Search Result Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="keywords" content="<?php echo createMetaData();?>">
<link rel="stylesheet" type="text/css" href="<?php echo $dir.'/css/bootstrap.min.css';?>">
<link rel="stylesheet" type="text/css" href="<?php echo $dir.'/css/jquery-ui.css';?>">
<link rel="stylesheet" type="text/css" href="<?php echo $dir.'/css/main.css';?>"/>
<script src="<?php echo $dir.'/js/libraries/jquery.js';?>"></script>
<script src="<?php echo $dir.'/js/libraries/bootstrap.min.js';?>"></script>
<script src="<?php echo $dir.'/js/libraries/jquery-ui.min.js';?>"></script>
<script src="<?php echo $dir.'/js/search.js';?>"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Search Result Page</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        </div>
      </div>
</nav>

<div class="jumbotron">
	<div class="container">
        <h2>Header</h2>

    </div>
</div>

<div class="container">
	<div class="row">
        <div class="col-md-2">
          <h3>Heading 1</h3>
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet 
        dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
        lobortis nisl ut aliquip ex ea commodo consequat.</p>
        </div>
        
        <div class="col-md-7">
            <!-- Header Buttons Please replace at will ---->
            <h3><?php echo $languageArr['results_Headings']['search_Result'];?></h3>
            <h4><a href="<?php if ( $dir == '' ) { echo '/'; } else { echo $dir; } ?>"><?php echo $languageArr['results_Headings']['new_Search'];?></a></h4>
            <!-- End Header Buttons  ---->
			<?php 
			    echo displaySearchResults(); 
			    //createHiddenParam();
			?>
       </div>
       
       <div class="col-md-3">
        <h3>Heading 2</h3>
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet 
        dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
        lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit 
        esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim 
        qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
        </p>
    </div> 
	</div>
    
    <hr>
	<footer>
    	<p>&copy; Company 2014</p>
	</footer>  
</div>

</body>
</html>
