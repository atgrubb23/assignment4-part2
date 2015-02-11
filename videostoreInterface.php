<?php
include('userInfo.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'/>
		<title>Videostore Interface</title>
		<style>
		fieldset {
		display: inline-block;
		}
		label {
		display: block;
		}
		input {
		display: block;
		margin: 6px;
		}
		input[type="submit"] {
		position: relative;
		left: 25%;
		}
		</style>
	</head>

	</body>
		<?php
		//Connect to database
		$vsMysqli = new mysqli($myHost, $myUsername, $myPassword, 
   			$myDatabase);
		if(!$vsMysqli || $vsMysqli->connect_errno) {
			echo "Error trying to connect to " . $myHost . "."
			   . $vsMysqli->connect_errno . " " . 
			   $vsMysqli->connect_error;
		}
		else {
			echo "Connected to " . $myHost . " successfully.";
		}
		?>
		<div>
			<form action='vsInputVal.php' method='GET'>
			<fieldset>
			<legend>Add Video</legend>
				<label for='videoName'>Enter video name: </label>	
				<input type='text' id='videoName' name='videoName'/>
				<label for='videoCategory'>Enter video category: </label>
				<input type='text' id='videoCategory' name='videoCategory'/>
				<label for='videoLength'>Enter video length: </label>
				<input type='text' id='videoLength' name='videoLength'/> 
				<input type='submit' Submit!/>
			</fieldset>
			</form>
		</div>
		
	</body>
</html>
