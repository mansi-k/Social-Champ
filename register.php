<?php 
session_start();
include_once 'dbconn.php';
include('server.php')

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
<body>
</head>

 <div class="container">
  

    <form class="well form-horizontal" action="register.php" method="post"  id="contact_form">
	
<fieldset>

<!-- Form Name -->
<legend><center><h2><b>Signup Form</b></h2></center></legend><br>

 <form data-toggle="validator" role="form">
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">Name</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input  name="name" placeholder="Name" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
      

      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">Phone #</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            <input name="phone" placeholder="(845)555-1212" class="form-control" type="text" required>
          </div>
        </div>
      </div>
	  
	  <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label">E-Mail</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input name="email" placeholder="E-Mail Address" class="form-control"  type="text" required >
          </div>
        </div>
      </div>
      
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">Address</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input name="address" placeholder="Address" class="form-control" type="text" required>
          </div>
        </div>
      </div>
      
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">City</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input name="city" placeholder="city" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
      
   
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">Pin Code</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input name="pin" placeholder="Pin Code" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
      
     
     <div class="form-group">
        <label class="col-md-4 control-label">Type</label>
        <div class="col-md-6 selectContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
            <select name="type" class="form-control selectpicker" >
              <option value="" >Please select your type</option>
              <option value="donor">Donor</option>
              <option value="promoter">Promoter</option>
              <option value="prospective">Prospective</option>
              <option value="social_admin">Social Admin</option>
			  </select>
          </div>
        </div>
      </div>
        
     
  
      <!-- Button -->
      <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning" name="reg_user">Create<span class="glyphicon glyphicon-send"></span></button>
        </div>
      </div>
    </fieldset>
  </form>
  <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
  <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
  </body>
</html>
	  