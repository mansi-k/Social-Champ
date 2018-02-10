<!DOCTYPE html>
<html>
<head>
<style>
#container
{
    margin-left: auto;
    margin-right: auto;
    width: 96%;
	
}
#btn1
{
	
	margin-left: 4%;
}


#btn2
{
	margin-top: 13px;
	margin-left: 28%;

	
}
.btn-primary {
	font-size:18px !important;
	min-width:345px;
	padding:25px 60px !important;
}
</style>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>

</head>
<body>
 <div class="container">
 
 <form class="well form-horizontal" action="home1.php" method="post"  id="contact_form">
	
<fieldset>
 
 <legend><center><h2 style="font-size:60px"><b>NGO Admin Panel</b></h2></center></legend>
  <div class="btn-group dropup" id="btn1">
  <h2><br><a class="btn btn-primary one" href = "register.php"  role = "button"><b>Register new user</b></a></br></h2>
 <h3><br><a class="btn btn-primary two" href ="register.php"  role="button"><b>Donate</b></a></br></h3>
 <h4><br><br><a class="btn btn-primary three" href ="register.php"  role="button"><b>Custom scan</b></button></a></br></h4>
 </button>
  </div>
  <div class="btn-group dropup" id="btn2">
  <h2><br><a href ="register.php" class="btn btn-primary four" role="button"><b>View your profiles</b></a></br></h2>
  <h3><br><a href ="register.php" class="btn btn-primary five" role="button"><b>View Social Profiles</b></a></br></h3>
  <h4><br><br><a href ="register.php" class="btn btn-primary six" role="button"><b>View Social Champions</b></a></br></h4>
  </button>
   </div>

  </div>
  </fieldset>
  </form>
  </body>
</html>
	  