<?php
include_once 'dbconn.php';
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> 
</head>

<body>
<div class="container">
<br></br>
<table class="table table-bordered table-hover table-condensed">
<thead>
      <tr>
        <th><center>id</center></th>
        <th><center>name</center></th>
		<th><center>type</center></th>
      </tr>
</thead>
<?php
$sql = mysqli_query($conn,"SELECT a.so_type_id, a.so_id, a.so_name, b.ot_type FROM ngo_social_objects AS a, social_object_types AS b WHERE a.so_type_id=b.ot_id AND a.at_id=1 AND a.u_id IN (SELECT u_id FROM user_extended WHERE ut_id=4)");

if (mysqli_num_rows($sql) > 0) {
    // output data of each row
    while($row = mysqli_fetch_array($sql)) {
		echo "<tr>";
		
        echo "<td><center>". $row["so_id"]."</center></td>";
		echo "<td><center>". $row["so_name"]."<center></td>";
		echo "<td><center>". $row["ot_type"]."<center></td>";
		echo "</tr>";
		
	
    }
}
 else {
    echo "0 results";
}

?>
</table>
</div>

<div class="container">
<form class="well form-horizontal" action="social_object.php" method="post">
<fieldset>
<form data-toggle="validator" role="form">
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">ID</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input  name="id" placeholder="Enter ID" class="form-control"  type="text" required>
          </div>
        </div>
      </div>
	  
	  <div class="form-group">
        <label class="col-md-4 control-label">Type</label>
        <div class="col-md-6 selectContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
            <select name="type" class="form-control selectpicker" >
              <option value="" >Please select your type</option>
              <option value="page">Page</option>
              <option value="event">Event</option>
              <option value="group">Group</option>
   
			  </select>
          </div>
        </div>
      </div>
	  
	  <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning" name="submit">Submit<span class="glyphicon glyphicon-send"></span></button>
        </div>
      </div>
</fieldset>
  </form>
  
 <?php
 include_once 'dbconn.php';

 $id ="";
 $type = "";
 if (isset($_POST['submit'])) {
 
  $id = $_POST['id'];
  $type = $_POST['type'];
     $objget = $fb->get($id . '?fields=id,name');
     $obj = $objget->getGraphNode();
     $a = array('id' => $obj['id'], 'name' => $obj['name'], 'type' => $type, 'uid' => $_SESSION['uid'], 'token' => $_SESSION['fb_access_token'], 'sw' => 'yes');
     fetchPeople($a, 1);
    header("Location:social_object.php");
 //$sql = "INSERT INTO ngo_social_objects (u_id,so_id)
//VALUES (1,'$id')";
//$result = mysqli_query($conn,$sql);

}
 $conn->close();
 ?>
</body>
</html>




