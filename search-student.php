<?php

$error = '';
$id = '';
$data = array();
$result = array();

function clean_text($string)
{
	$string = trim($string);
	$string = stripslashes($string);
	$string = htmlspecialchars($string);
	return $string;
}

if(isset($_POST["submit"]))
{
	//Id Validation
	if(empty($_POST["id"]))
		$error .= '<p><label class="text-danger">Please Enter a Id!!</label></p>';
	else
	{
		$id = clean_text($_POST["id"]);
		if(!preg_match("/^[0-9]*$/",$id))
			$error .= '<p><label class="text-danger">Only numbers allowed for Student ID!!</label></p>';
	}

	// Check for errors
	if($error == '')
	{
		$file = fopen('students.csv', 'r');
		
		while (($line = fgetcsv($file)) !== FALSE) {
			//$line is an array of the csv elements
			array_push($data,$line);
		}
		foreach ($data as $index => $value) 
		{
			if($index!=0 && $value[0] == $id) array_push($result, $value);
		}
		if(empty($result)) $error .= '<p><label class="text-danger">No Search Results Found</label></p>';
		fclose($file);
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Search a Student</title>
</head>
<body>
	<!-- Navbar -->
	<?php include 'includes/navbar.php'; ?>
	<!-- Navbar ends -->
	<div class="container">
		<br>
		<div class="col-md-6" style="margin:0 auto; float:none;">
			<form method="post">
				<div class="form-group">
					<label>Student ID</label>
					<input type="text" name="id" placeholder="Enter ID to be searched" class="form-control" value="<?php echo $id; ?>" />
				</div>
				<div class="form-group" style="float: right">
					<input type="submit" name="submit" class="btn btn-info" value="Submit" />
				</div>
			</form>
			<br>
			<h3 align="center"><?php echo $error; ?></h3>
			<br />
			<?php if(!empty($result)) { ?>
			<h3 align="center"> Below are details of your query: </h3>
			<div class="table-responsive">
	    		<table class="table table-bordered table-hover">
	      			<tbody>
	      				<?php for($i =0; $i < count($result[0]); $i++){ ?>
	        			<tr>
							<th><?php echo $data[0][$i] ?></th>
							<td><?php echo $result[0][$i] ?></td>
	        			</tr>
	        			<?php } ?>
	      			</tbody>
	    		</table>
	  		</div>
	  		<?php } ?>
		</div>
	</div>
</body>
</html>