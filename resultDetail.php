<?php include 'webkitConfig.php';?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="keywords" content="<?=displayMetaTags()?>">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/main.css"/>
<link rel="stylesheet" type="text/css" href="../css/font-awesome.css"/>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body onLoad="<?php if($showMap) echo 'initialize()';?>">

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Detail Page</a>
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
          <?=displayPropertyDetail();?>
       </div>
       
       <div class="col-md-3">
        <div>
        <?=contactForm();?>
        </div>
        
        <h3>Heading 2</h3>
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet 
        dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
        lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit 
        esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim 
        qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.</p>
        
    </div> 
	</div>
    
    <hr>
	<footer>
    	<p>&copy; Company 2014</p>
	</footer>  
</div>
<script src="../js/libraries/jquery.js"></script>
<script src="../js/libraries/bootstrap.min.js"></script>
<script src="../js/libraries/jquery-ui.min.js"></script>
<script src="../js/jquery-datepicker/i18n/datepicker-<?php echo $datePickerLanguages[$language];?>.js"></script>
<script src="../js/jquery.resizecrop-1.0.3.js"></script>
<script src="../js/validate.js"></script>
<script src="../js/contactForm.js"></script>
<?php if ( $showMap ) { ?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&components=country:ES&language=<?php echo $mapLanguages[$language];?>"></script>
<script src="../js/map.js"></script>
<?php } ?>
<script src="../js/imageViewer.js"></script>
<script src="../js/bookingCalendar.js"></script>
</body>
</html>
