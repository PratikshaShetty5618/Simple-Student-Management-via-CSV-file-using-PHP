<?php

$error = '';
$id = '';
$name = '';
$gender = '';
$dob = '';
$city = '';
$state = '';
$email = '';
$qualification = '';
$stream = '';

function clean_text($string)
{
	$string = trim($string);
	$string = stripslashes($string);
	$string = htmlspecialchars($string);
	return $string;
}

function string_validation($string)
{	

	if(empty($_POST[strval($string)]))
		return '<p><label class="text-danger">Please Enter your ' . ucfirst($string) . '!!</label></p>';
	else
	{
		$str = $string;
		$string = clean_text($_POST[strval($string)]);
		if(!preg_match("/^[a-zA-Z .()]*$/",$string)) 
			return '<p><label class="text-danger">Only letters and white space allowed for ' . ucfirst($str) . '</label></p>';
	}
	return "true";
}

function isDate($string) 
{
    $matches = array();
    $pattern = '/^([0-9]{1,2})\\-([0-9]{1,2})\\-([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches)) return false;
    if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
    return true;
}

//Very Helpful Function to add data to new line
// Writes an array to an open CSV file with a custom end of line.
//
// $fp: a seekable file pointer. Most file pointers are seekable, 
// but some are not. example: fopen('php://output', 'w') is not seekable.
// $eol: probably one of "\r\n", "\n", or for super old macs: "\r"
// Mostly, windows:"\r\n" and linux:"\n"
function fputcsv_eol($fp, $array, $eol) {
  fputcsv($fp, $array);
  if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
    fwrite($fp, $eol);
  }
}

//Form Validation
if(isset($_POST["submit"]))
{
	//Id Validation
	if(empty($_POST["id"]))
		$error .= '<p><label class="text-danger">Please Enter your Id!!</label></p>';
	else
	{
		$id = clean_text($_POST["id"]);
		if(!preg_match("/^[0-9]*$/",$id))
			$error .= '<p><label class="text-danger">Only numbers allowed for Student ID!!</label></p>';
	}

	// Name Validation
	if(string_validation("name") != "true") $error .= string_validation("name");
	$name = clean_text($_POST["name"]);

	// Gender Validation
	if(empty($_POST["gender"]))
		$error .= '<p><label class="text-danger">Please Select your Gender!!</label></p>';
	else
		$gender = clean_text($_POST["gender"]);

	// DOB Validation
	if(empty($_POST["dob"]))
		$error .= '<p><label class="text-danger">Please Enter your Date Of Birth!!</label></p>';
	else
	{
		$dob = $_POST["dob"];
		if(!isDate($dob))
			$error .= '<p><label class="text-danger">Please Enter valid Date Of Birth!!</label></p>';
	}

	// City Validation
	if(string_validation("city") != "true") $error .= string_validation("city");
	$city = clean_text($_POST["city"]);

	// State Validation
	if(string_validation("state") != "true") $error .= string_validation("state");
	$state = clean_text($_POST["state"]);

	// Email Validation
	if(empty($_POST["email"]))
		$error .= '<p><label class="text-danger">Please Enter your Email</label></p>';
	else
	{
		$email = clean_text($_POST["email"]);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			$error .= '<p><label class="text-danger">Invalid email format</label></p>';
	}

	// Qualification Validation
	if(string_validation("qualification") != "true") $error .= string_validation("qualification");
	$qualification = clean_text($_POST["qualification"]);

	// Stream Validation
	if(string_validation("stream") != "true") $error .= string_validation("stream");
	$stream = clean_text($_POST["stream"]);


	// If no error in Form Submission then add data
	if($error == '')
	{
		$file_open = fopen("students.csv", "a+");

		// Check if data already exists
		$present = FALSE;
		while (($line = fgetcsv($file_open)) !== FALSE) {
			// Check if Provided Id already exists in each row
			foreach ($line as $key => $value) {
				if($value[0] == $id) $present = TRUE;
			}
		}

		// If data is not present then add data Otherwise notify user about data already existing
		if (!$present)
		{
			$form_data = array(
				'Student Id'  => $id,
				'Name'  => $name,
				'Gender' => $gender,
				'DateOfBirth' => $dob,
				'City' => $city,
				'State' => $state,
				'EmailId'  => $email,
				'Qualification' => $qualification,
				'Stream' => $stream,
			);
			fputcsv_eol($file_open, $form_data,"\r\n");
			fclose($file_open);
			$error = '<label class="text-success">Student Added Successfully!!</label>';
			$id = '';
			$name = '';
			$gender = '';
			$dob = '';
			$city = '';
			$state = '';
			$email = '';
			$qualification = '';
			$stream = '';
		}
		else $error = '<label class="text-success">Student Already Exists with same Id!!</label>';
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add a Student</title>
</head>
<body>
	<!-- Navbar -->
	<?php include 'includes/navbar.php'; ?>
	<!-- Navbar ends -->
	
	<br />
	<div class="container">
		<h2 align="center">Add a Student</h2>
		<h4 align="center">Fill the below details</h4>
		<br />
		<div class="col-md-6" style="margin:0 auto; float:none;">
			<form method="post">
				<h3 align="center"><?php echo $error; ?></h3>
				<br />
				<div class="form-group">
					<label><b>Student ID</b></label>
					<input type="text" name="id" placeholder="Enter Student ID" class="form-control" value="<?php echo $id; ?>" />
				</div>
				<div class="form-group">
					<label><b>Student Name</b></label>
					<input type="text" name="name" placeholder="Enter Student Name" class="form-control" value="<?php echo $name; ?>" />
				</div>
				<div class="form-check">
					<label><b>Gender</b></label><br>
					<input type="radio" name="gender" class="form-check-input" id="male" 
					<?php if (isset($gender) && $gender=="Male") echo "checked";?> 
					value="Male" />
					<label class="form-check-label" for="male"> Male </label><br/>
					<input type="radio" name="gender" class="form-check-input" id="female" 
					<?php if (isset($gender) && $gender=="Female") echo "checked";?>
					value="Female" />
					<label class="form-check-label" for="female"> Female </label>
				</div>
				<div class="form-group">
					<label><b>Date Of Birth</b></label>
					<div class='input-group date' id='datetimepicker1'>
						<input type='text' name="dob" class="form-control" placeholder="DD-MM-YYYY" value="<?php echo $dob; ?>" />
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
				<div class="form-group">
					<label><b>City</b></label>
					<input type="text" name="city" placeholder="Enter City where student resides." class="form-control" value="<?php echo $city; ?>" />
				</div>
				<div class="form-group">
					<label><b>State</b></label>
					<input type="text" name="state" placeholder="Enter State where student resides" class="form-control" value="<?php echo $state; ?>" />
				</div>
				<div class="form-group">
					<label><b>Email Id</b></label>
					<input type="text" name="email" class="form-control" placeholder="Enter Email Id of student/parents/guardian." value="<?php echo $email; ?>" />
				</div>
				<div class="form-group">
					<label><b>Qualification</b></label>
					<input type="text" name="qualification" class="form-control" placeholder="Enter Student's Qualification" value="<?php echo $qualification; ?>" />
				</div>
				<div class="form-group">
					<label><b>Stream</b></label>
					<input type="text" name="stream" class="form-control" placeholder="Enter Student's opted Stream" value="<?php echo $stream; ?>" />
				</div>
				<div class="form-group" align="center">
				<input type="submit" name="submit" class="btn btn-info" value="Submit" />
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		$(function () {
		var bindDatePicker = function() {
			$(".date").datetimepicker({
				format:'DD-MM-YYYY',
				icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-arrow-up",
					down: "fa fa-arrow-down"
				}
			})
		}
		bindDatePicker();
		});
	</script>
</body>
</html>