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
        <h1>Parent List</h1>
		<br />
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Student Roll#</th>
					<th>Student Name</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$query = $db->query("SELECT * FROM `parent` ORDER BY `id` ASC");
					$count = 1;
					while($fetch = $query->fetch_array()){
                        $studentName = "";
                        $studentRoll = "";
				?>
				<tr>
					<td><?php echo $count++?></td>
					<td><?php echo $fetch['name']?></td>
					<td><?php echo $fetch['email']?></td>
                    <?php
                        $query1 = $db->query("SELECT * FROM `student` WHERE `id` = " . $fetch['student_id']);
                        while($fetch1 = $query1->fetch_array()){
                            $studentName = $fetch1['name'];          
                            $studentRoll = $fetch1['id'];          
                        }
                    ?>
					<td><?php echo $studentRoll?></td>
					<td><?php echo $studentName?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
        <p>Back to Admin Management Parent Dashboard <a href="AdminManageParent.php">Click Here</a>.</p>
	</div>

</body>
</html>