<?php
require "config.php";
error_reporting(0);
//Define $originalArray with Null Point
$originalArray = array();

try{
	$stmt = $dbh->prepare("SELECT seniorUID FROM users");
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$data = $stmt->fetchAll();
}catch(PDOException $e){
	echo $e->getMessage();
}

foreach($data as $datum){
	array_push($originalArray, $datum->seniorUID);
}

//Make Copy of Original Array to Shuffle
$shuffleArray = $originalArray;
shuffle($shuffleArray);

//Make Copy of Shuffled Array to Shift
$shiftArray = $shuffleArray;
$firstChildArray = array_shift($shiftArray);

//Append First Child to End of Array
array_push($shiftArray, $firstChildArray);
//Store Shift & Shuffle Array with Shuffle = Key & Shift = Value
$idArray = array_combine($shuffleArray, $shiftArray);

foreach ($idArray as $key => $value) {
	try{
		$stmt = $dbh->prepare("UPDATE users SET targetSeniorUID = :targetSeniorUID WHERE seniorUID = :seniorUID");
		$stmt->bindParam(":targetSeniorUID", $value, PDO::PARAM_INT, 11);
		$stmt->bindParam(":seniorUID", $key, PDO::PARAM_INT, 11);
		$stmt->execute();
	}catch(PDOException $e){
		echo $e->getMessage();
	}	
}
?>

