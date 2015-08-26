<?php include 'webkitConfig.php';?>

<!DOCTYPE html> <!-- doctype need to be on the first line -->
<html>
<head>
<title>Search Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.css"/>
<link rel="stylesheet" href="css/chosen.min.css">
<link rel="stylesheet" type="text/css" href="css/main.css"/>

<script src="js/libraries/jquery.js"></script>
<script src="js/libraries/bootstrap.min.js"></script>
<script src="js/libraries/jquery-ui.min.js"></script>
<script src="js/jquery-datepicker/i18n/datepicker-<?php echo $datePickerLanguages[$language];?>.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/SearchForm.js"></script>

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
          <a class="navbar-brand" href="#">Search Page</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        </div>
      </div>
</nav>

<div class="jumbotron">
	<div class="container">
    	<h1>Header</h1>
    </div>
</div>

<div class="container">
	<div class="row">
        <div class="col-sm-12 col-md-4">
            <h2>Heading</h2>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet 
            dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
            lobortis nisl ut aliquip ex ea commodo consequat.</p>
			<?php echo autoSearchLink('Villas in Benalmadena', 'Benalmadena', 'House'); ?><br />
            <?php echo autoSearchLink('Apartments in Marbella', 'Marbella', 'Apartment'); ?><br />
            <?php echo autoSearchLink('Plots in Estepona', 'Estepona', 'Plot'); ?><br />
            <?php echo autoSearchLink('Studio Apartments in Elviria', 'Elviria', 'Apartment','/ForSale/','Costa del Sol', '1-8'); ?><br />

        </div>
        
        <div class="col-sm-12 col-md-4">
            <h2>Sub Heading</h2>
            <p>
            
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet 
            dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
            lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit 
            esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim 
            qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.
            </p>
            <p>
            Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo 
            consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore 
            eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit 
            augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy 
            nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
            </p>
        </div>
       
		<div class="col-sm-12 col-md-4">
        	<?php displaySearchForm(); ?>
		</div> 
	</div>
</div>
<div class="container">
    <div class="property_feature">
        <h2>Featured Properties</h2>
        <?php displayFeatureResults(); ?>
    </div>
</div>
<div class="container">
    <hr>
	<footer>
    	<p>&copy; Company 2014</p>
	</footer>
</div>
</body>
</html>
