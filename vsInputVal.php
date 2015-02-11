<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
	   $failedParams = array();
	   function isInt($string) {
		   if(!is_numeric($string) || strpbrk($string, '.') || strpbrk($string, '-')) {
			   return false;
		   }
		   return true;
	   }
	   function isValidString($string) {
	      if(strlen($string) > 255) {
	      	$failedParams[] = "<p>Entry cannot exceed 255 characters.";
          return false;
        }
      return(!is_numeric($string));
	   }
	   
	   if(isset($_GET['videoName'])) {
	      $name = $_GET['videoName'];
	      $name = explode("+", $name);
	      $name = implode($name);
	      if(!isValidString($name) || trim($name) === '') {
          $failedParams[] = "<p>The video name must be a string value and not null.";
	      }
	   }

	   if(isset($_GET['videoCategory'])) {
	      if(!isValidString($_GET['videoCategory'])) {
          $failedParams[] = "<p>The video category must be a string value.";
	      }
	   }

	   if(isset($_GET['videoLength'])) {
	      if(!isInt($_GET['videoLength'])) {
          $failedParams[] = "<p>The video length must be a positive integer value in minutes.";
	      }		   
	   }

	   if(!empty($failedParams)) {
		   foreach($failedParams as $value) {
		      echo "<p>$value";
		   }
		   exit();
	   }
     
	   $getParams = "?videoName=" . $name . "&videoCategory=" . $_GET['videoCategory'] . "&videoLength=" . $_GET['videoLength'];
	   header('Location: http://web.engr.oregonstate.edu/~grubba/cs290/assignment4-pt2/videostoreInterface.php' . $getParams);
   ?>
