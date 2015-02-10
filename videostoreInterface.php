<?php
include('userInfo.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'/>
		<title>Videostore Interface</title>
	</head>

	</body>
		<?php
		//Connect to database
		$vsMysqli = new mysqli($myHost, $myUsername, $myPassword, 
   			$myDatabase);
		if(!$vsMysqli || $vsMysqli->connect_errno) {
			echo "Could error trying to connect to " . $myHost . "."
			   . $vsMysqli->connect_errno . " " . 
			   $vsMysqli->connect_error;
		}
		else {
			echo "Connected to " . $myHost . " successfully.";
		}
		?>
	</body>
</html>
