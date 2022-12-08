<?php
// Include config file
include("config.php");
session_start();
// Define variables and initialize with empty values
$pname = trim($_SESSION['login_user']);
$name = "";
$name_err  = "";
$code = "";
$code_err = $aID_err  = "";
$status = "Submitted";
$status_err  = "";
$sID= -1;
$dID= -1;
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminManageDegreeRemove.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>


<body>
    <div class="bodyDiv">
        <div class="listContianer">
        
        <h1>Applications List</h1>
            <br />
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Degree Name</th>
                        <th>Degree Code</th>
                        <th>Degree Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $query = $db->query("SELECT s.email as email FROM parent p INNER JOIN student s on p.student_id = s.id WHERE p.email = '".$pname."'");
                        while($fetch = $query->fetch_array()){
                            $name = $fetch['email'];
                        }
                        $query = $db->query("SELECT a.status as astatus, d.name as dname, d.code as dcode, d.duration as duration FROM applications a INNER JOIN student s on s.id = a.student_id INNER JOIN degree d on d.id = a.degree_id where s.email = '".$name."'");
                        $count = 1;
                        while($fetch = $query->fetch_array()){
                    ?>
                    <tr>
                        <td><?php echo $count++?></td>
                        <td><?php echo $fetch['dname']?></td>
                        <td><?php echo $fetch['dcode']?></td>
                        <td><?php echo $fetch['duration']?></td>
                        <td><?php echo $fetch['astatus']?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <p>Logout<a href="logout.php">Click Here</a>.</p>

        </div>
    </div>    
</body>
</html>


