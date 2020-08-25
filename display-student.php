<?php

$data = array();

$file = fopen('students.csv', 'r');

while (($line = fgetcsv($file)) !== FALSE) {
	//$line is an array of the csv elements
	array_push($data,$line);
}
fclose($file);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Students List</title>
</head>
<body>
	<!-- Navbar -->
	<?php include 'includes/navbar.php'; ?>
	<!-- Navbar ends -->
	<br>
	<div class="container">
		<?php if(!empty($data)) { ?>
		<h2 align="center"> Students Data </h2>
		<div class="table-responsive">
    		<table class="table table-bordered table-hover">
      			<thead>
        			<tr>
          				<th>#</th>
          				<?php for($i = 0; $i < count($data[0]); $i++){ ?>
          				<th><?php echo $data[0][$i] ?></th>
          				<?php } ?>
        			</tr>
      			</thead>
      			<tbody>
      				<?php for($i = 1; $i < count($data); $i++){ if(!empty($data[$i][1])) {?>
        			<tr>
						<td><?php echo $i ?>)</td>
						<?php for($j = 0; $j < count($data[$i]); $j++){ ?>
						<td><?php echo $data[$i][$j] ?></td>
						<?php } ?>
        			</tr>
        			<?php }} ?>
      			</tbody>
    		</table>
  		</div>
  		<?php } ?>
	</div>
</body>
</html>