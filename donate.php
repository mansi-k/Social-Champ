<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container">
<?php

include 'dbconn.php' ;



$sql="select distinct name from user where u_id in(select u_id from user_extended 
 where ut_id in (1,2))";

$result=mysqli_query($conn,$sql);


if($result === FALSE) {
die(mysqli_error());  
}
?>
<form class="well form-horizontal" action="donate2.php" method="post">
<fieldset>
<form data-toggle="validator" role="form">
<div class="form-group">
        <label class="col-md-4 control-label">name:</label>
        <div class="col-md-6 selectContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

<?php
//echo "<select name='name'>";
echo "<select name='name' class='form-control selectpicker'>";
echo "<option value='' >Please select your name</option>";
while ($row = mysqli_fetch_array($result)) {

echo "<option value='" . $row['name'] ."'>" . $row['name']."</option>";
}
echo "</select>";
?>
</div>
</div>
  </div>
  

	<div class="form-group">
        <label class="col-md-4 control-label">Amount:</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input  name="amount" placeholder="Enter the amount" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
      
  
  <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning" name="submit">Submit<span class="glyphicon glyphicon-send"></span></button>
        </div>
      </div>
  
  
  
</form>
</fieldset>
</body>
</html>