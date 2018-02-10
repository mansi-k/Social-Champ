
<html>
<body>

<?php

session_start();
include_once 'dbconn.php';
require_once 'fboauth.php';
include('server.php');
if(isset($_SESSION['u_id']))
	{
echo "<br><br><br>".'<center><b>'."Connect with us on social media. Help us reach out to more people! <br>Join us with user id:".$_SESSION['u_id']."<br><br>".'<b><center>';
	}
echo "<br>".'<center>'.'<a href="' . $loginUrl . '"><img src="facebook.png" height="50px"/></a>'."<br><br>".'<center>';
echo '<center>'."<a href='redirect.php '><img src='twitter.png' height='60px'/></a>"."<br><br>".'<center>';
echo '<center>'."<a href=' register.php'>Go back</a>".'<center>';

if(isset($_SESSION['success'])) {?> 
 <script type="text/javascript">
alert("Done"); 
</script>
<?php } ?>
</body>
</html>