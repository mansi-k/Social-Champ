<!DOCTYPE html>
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
<h3>SCI</h3>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active">
  <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Facebook <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="#own" role="tab" data-toggle="tab">Owned by donor/promoter</a></li>
				<li><a href="#other" role="tab" data-toggle="tab">Others</a></li>
            </ul>
		</li>  
	</li>
   <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Twitter <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="#face" role="tab" data-toggle="tab">Facebook</a></li>
                <li><a href="#twit" role="tab" data-toggle="tab">Twitter</a></li>
            </ul>
    </li> 
</ul>


<!-- facebook -->
<div class="tab-content">
<!--Owned-->
		<div class="tab-pane fade in active" id="own">
		<br>
			<div class="container">
			<ul class="nav nav-pills">
				<li><a data-toggle="pill" href="#event">Event</a></li>
				<li><a data-toggle="pill" href="#page">Page</a></li>
				<li><a data-toggle="pill" href="#group">Group</a></li>
			</ul>
					<div class="tab-content">
	<!--event-->		<div id="event" class="tab-pane fade in active ">
						<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Owner</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
							<?php
							$abcd=mysqli_query($conn,"SELECT a.*, b.ot_type FROM ngo_social_objects as a,social_object_types as b where a.so_type_id=b.ot_id and a.at_id=1 and a.so_type_id=3  and a.u_id in (SELECT u_id FROM user_extended as u WHERE u.ut_id!=4)ORDER BY a.so_score  DESC");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td>
														<?php $xy=mysqli_query($conn,"SELECT name from user where u_id='".$row['u_id']."'");
														$arr=mysqli_fetch_array($xy);
														?>
														<?php $abc=mysqli_query($conn,"SELECT account_id from user_accounts where u_id='".$row['u_id']."'");
														$arr1=mysqli_fetch_array($abc);
														?>
														<a href="http://www.facebook.com/<?php echo $arr1['account_id'];?>" target="_blank" ><?php echo $arr['name'];?></a>
													</td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>
												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>
		<!--page-->			<div id="page" class="tab-pane ">
								<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Owner</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
							<?php
							$abcd=mysqli_query($conn,"SELECT * FROM ngo_social_objects as a,social_object_types as b where a.so_type_id=b.ot_id and a.at_id=1 and a.so_type_id=1  and a.u_id in (SELECT u_id FROM user_extended as u WHERE u.ut_id!=4)ORDER BY a.so_score  DESC");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td>
														<?php $xy=mysqli_query($conn,"SELECT name from user where u_id='".$row['u_id']."'");
														$arr=mysqli_fetch_array($xy);
														?>
														<?php $abc=mysqli_query($conn,"SELECT account_id from user_accounts where u_id='".$row['u_id']."'");
														$arr1=mysqli_fetch_array($abc);
														?>
														<a href="http://www.facebook.com/<?php echo $arr1['account_id'];?>" target="_blank" ><?php echo $arr['name'];?></a>
													</td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>
												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>
			<!--Group-->		<div id="group" class="tab-pane ">
								<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Owner</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
							<?php
							$abcd=mysqli_query($conn,"SELECT * FROM ngo_social_objects as a,social_object_types as b where a.so_type_id=b.ot_id and a.at_id=1 and a.so_type_id=2  and a.u_id in (SELECT u_id FROM user_extended as u WHERE u.ut_id!=4)ORDER BY a.so_score  DESC");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td>
														<?php $xy=mysqli_query($conn,"SELECT name from user where u_id='".$row['u_id']."'");
														$arr=mysqli_fetch_array($xy);
														?>
														<?php $abc=mysqli_query($conn,"SELECT account_id from user_accounts where u_id='".$row['u_id']."'");
														$arr1=mysqli_fetch_array($abc);
														?>
														<a href="http://www.facebook.com/<?php echo $arr1['account_id'];?>" target="_blank" ><?php echo $arr['name'];?></a>
													</td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>
												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>
					</div>
			</div>
		</div>
<!--Other-->
	<div class="tab-pane" id="other">
		<br>
			<div class="container">
			<ul class="nav nav-pills">
				<li><a data-toggle="pill" href="#event1">Event</a></li>
				<li><a data-toggle="pill" href="#page1">Page</a></li>
				<li><a data-toggle="pill" href="#group1">Group</a></li>
			</ul>
					<div class="tab-content">
		<!--event-->	<div id="event1" class="tab-pane fade in active">
						<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
								<?php
								$abcd=mysqli_query($conn,"SELECT a.*, b.ot_type from ngo_social_objects as a, social_object_types as b where u_id=11 and at_id=1 and so_type_id=3 and a.so_type_id=b.ot_id");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>

												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>
		<!--page-->	<div id="page1" class="tab-pane">
						<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
								<?php
								$abcd=mysqli_query($conn,"SELECT a.*, b.ot_type from ngo_social_objects as a, social_object_types as b where u_id=11 and at_id=1 and so_type_id=1 and a.so_type_id=b.ot_id");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>

												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>
		<!--group-->	<div id="group1" class="tab-pane">
						<br>
							<table class="table table-bordered">
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Mentions</th>
								<th>Scan</th>
							</tr>
								<?php
								$abcd=mysqli_query($conn,"SELECT a.*, b.ot_type from ngo_social_objects as a, social_object_types as b where u_id=11 and at_id=1 and so_type_id=2 and a.so_type_id=b.ot_id");
								if(mysqli_num_rows($abcd)>0)
								{
										$i=1;
										while($row=mysqli_fetch_array($abcd))
										{
													?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><a href="http://www.facebook.com/<?php echo $row['so_id'];?>" target="_blank" ><?php echo $row['so_name']; ?></td>
													<td><?php echo $row['so_score'];?></td>
													<td><a href="<?php echo 'extrascan.php?oid='.$row['so_id'].'&at=facebook&ot='.$row['ot_type']; ?>" target="_blank"><button type="button" class="btn">Scan</button></a></td>

												</tr>
											<?php
											$i++;
										}
									}?>

							</table>
							</div>

					</div>
			</div>
		</div>
		<div class="tab-pane" id="face">
		</div>
		
</div>