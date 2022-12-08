<!DOCTYPE html>
<?php
    require_once "config.php";
?>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
        <link rel="stylesheet" href="AdminStudentList.css">
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
	</head>
<body>
	<div class="container">
        <h1>Students List</h1>
		<br />
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Roll#</th>
					<th>Name</th>
					<th>Email</th>
					<th>City</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$query = $db->query("SELECT s.id as id, s.name as name,s.email as email,c.name as cname FROM student s INNER JOIN city c ON s.city_id=c.id");
					$count = 1;
					while($fetch = $query->fetch_array()){
				?>
				<tr>
					<td><?php echo $count++?></td>
					<td><?php echo $fetch['id']?></td>
					<td><?php echo $fetch['name']?></td>
					<td><?php echo $fetch['email']?></td>
					<td><?php echo $fetch['cname']?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
        <p>Back to Admin Management Student Dashboard <a href="AdminManageStudent.php">Click Here</a>.</p>
	</div>

</body>
</html>