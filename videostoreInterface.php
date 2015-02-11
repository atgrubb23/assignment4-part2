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
		input[type='text']{
      margin: 6px;
		}
    input[type='submit'] {
      display: block;
      margin: 2px;
    }
    table {
      margin: 2px;
      padding: 2px;
    }
    fieldset {
      margin: 10px;
    }
		</style>
	</head>

	</body>
		<?php
		//Connect to database
		$mysqli = new mysqli($myHost, $myUsername, $myPassword,
   			$myDatabase);
		if(!$mysqli || $mysqli->connect_errno) {
			echo "Error trying to connect to " . $myHost . "."
			   . $mysqli->connect_errno . " " .
			   $mMysqli->connect_error;
		}
		else {
			echo "Connected to Videostore Table at <a href=http://onid.oregonstate.edu/>$myHost</a> successfully.";
		}
		?>
		<div>
			<form action='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/vsInputVal.php' method='GET'>
			<fieldset>
			<legend>Add Video</legend>
				<label for='videoName'>Enter video name: </label>
				<input type='text' id='videoName' name='videoName'/>
				<label for='videoCategory'>Enter video category: </label>
				<input type='text' id='videoCategory' name='videoCategory'/>
				<label for='videoLength'>Enter video length: </label>
				<input type='text' id='videoLength' name='videoLength'/>
				<input type='submit' value='Submit!'/>
			</fieldset>
			</form>
		</div>
<?php
if(isset($_GET['videoName']) && isset($_GET['videoCategory']) && isset($_GET['videoLength'])) {
  $name = $_GET['videoName'];
  $category = $_GET['videoCategory'];
  $length = $_GET['videoLength'];

  $statement = $mysqli->prepare("INSERT INTO videostore(name, category, length) VALUES(?, ?, ?);");
  $statement->bind_param("ssi", $name, $category, $length);
  if(!$statement->execute()) {
    echo "<p>Insertion into videostore table failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
    echo "<p><a href='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php?'>Reload the page</a>";
  }
}

//Delete data for row
if(isset($_GET['Delete']) && $_GET['Delete'] !== 'All') {
  $statement = $mysqli->prepare("DELETE FROM videostore WHERE id = ?");
  $statement->bind_param("i", $_GET['Delete']);
  if(!$statement->execute()) {
    echo "<p>Deletion of row from videostore failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
  }
  $statement->close();
}

//Delete all videos
if(isset($_GET['Delete']) && $_GET['Delete'] === 'All') {
  $statement = $mysqli->prepare("DELETE FROM videostore;");
  if(!$statement->execute()) {
    echo "<p>Deletion of all rows from videostore failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
  }
  $statement->close();
 }

//Set video to rented
if(isset($_GET['Rent'])) {
  $statement = $mysqli->prepare("SELECT rented FROM videostore WHERE id = " . $_GET['Rent']);
  $statement->execute();
  $statement->bind_result($currentStatus);
  $statement->fetch();
  $statement->close();
  if($currentStatus === 1) {
    $statement = $mysqli->prepare("UPDATE videostore SET rented = 0 WHERE id = " . $_GET['Rent']);
  }
  else {
    $statement = $mysqli->prepare("UPDATE videostore SET rented = 1 WHERE id = " . $_GET['Rent']);
  }
  if(!$statement->execute()) {
  echo "<p>Failed to change rented status of video ID: " . $_GET['Rent'] . ". Error Number: " . $statement->errno . " Error Message: " . $statement->error;
  }
  $statement->close();
}

//Retrieve data for category drop down menu
$statement = $mysqli->prepare("SELECT category FROM videostore;");
if(!$statement->execute()) {
  echo "<p>Retrieval of category data from videostore failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
}
$categoryArray = array();
$categoryArray[] = "Select a category...";
$categoryArray[] = "All Movies";
$statement->bind_result($thisCategory);
while($statement->fetch()) {
  $categoryArray[] = $thisCategory;
}

//Retrieve data for displaying All Movies
if(!isset($_GET['CategoryFilter']) || $_GET['CategoryFilter'] === 'All') {
$statement = $mysqli->prepare("SELECT id, name, category, length, rented FROM videostore;");
if(!$statement->execute()) {
  echo "<p>Retrieval of table data from videostore failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
}
$statement->bind_result($vId, $vName, $vCat, $vLen, $vRent);
}

//Retrieve data for displaying filtered movies
if(isset($_GET['CategoryFilter']) && $_GET['CategoryFilter'] !== 'All') {
  $statement = $mysqli->prepare("SELECT id, name, category, length, rented FROM videostore WHERE category = '" . $_GET['CategoryFilter'] . "';");
  if(!$statement->execute()) {
  echo "<p>Application of category filter failed: Error Number: " . $statement->errno . " Error Message: " . $statement->error;
  }
  $statement->bind_result($vId, $vName, $vCat, $vLen, $vRent);
}
?>

    <div>
      <fieldset>
      <legend>Videos</legend>
      <form action='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php' method='GET'>
        <select name='CategoryFilter' onchange='this.form.submit()'>
<?php
//Populate category drop down filters 
$categoryArray = array_unique($categoryArray);
foreach($categoryArray as $value) {
  echo "<option value=$value>$value</option>";
}
?>
        </select>
      </form>
      <table border=1>
        <tr><th></th> <th></th> <th>Name</th> <th>Category</th> <th>Length (min.)</th> <th>Rented</th>
<?php
while($statement->fetch()) {
 echo "<tr> 
  <td>
    <form action='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php' method='GET'>
      <button type='submit' name='Delete' value='$vId' onclick='this.form.submit()'>Delete</button>
    </form>
  </td> 
  <td>
    <form action='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php' method='GET'>
      <button type='submit' name='Rent' value='$vId' onlick='this.form.submit()'>Rent</button>
    </form>
  </td> 
  <td>" . $vName . "</td> 
  <td>" . $vCat . "</td> 
  <td>" . $vLen . "</td> 
  <td>" . $vRent . "</td> 
  </tr>";
}
?>
      </table>
      <form action='http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php' method='GET'>
        <button type='submit' name='Delete' value='All' onclick='this.form.submit()'>Delete All Videos</button>
      </fieldset>
    </div>
	</body>
</html>
