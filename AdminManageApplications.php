<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = "";
$name_err  = "";
$code = "";
$code_err = $aID_err  = "";
$status = "Submitted";
$status_err  = "";
$sID= -1;
$dID= -1;
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
            // Validate confirm password
            if(empty(trim($_POST["code"]))){
                $code_err = "Please enter valid code.";     
            } else{
                $code = trim($_POST["code"]);
            }
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter valid name.";     

    }else{
        
        $query = $db->query("SELECT id FROM degree WHERE code = '".trim($_POST["code"])."'");
        while($fetch = $query->fetch_array()){
            $dID = $fetch['id'];
        }
        if($dID == -1){
            $code_err = "Please enter valid code.";     
        }else{
        // Prepare a select statement
        $sql = "SELECT id FROM student WHERE email = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["name"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){

                    $query = $db->query("SELECT id FROM student WHERE email = '".$param_username."'");
                    while($fetch = $query->fetch_array()){
                        $sID = $fetch['id'];
                    }
                    $query = $db->query("SELECT id FROM applications WHERE student_id = ".$sID." AND degree_id = ".$dID."");
                    $aID = -1;
                    while($fetch = $query->fetch_array()){
                        $aID = $fetch['id'];
                    }
                    if($aID == -1){

                        $aID_err = "No Such Record Found.";
                        
                    }else{
                        $name = trim($_POST["name"]);
                        $code = trim($_POST["code"]);
                        $delSql = "UPDATE applications SET status = ? WHERE student_id = ? AND degree_id = ? ";
                        if($stmtDel = mysqli_prepare($db, $delSql)){
    
                        mysqli_stmt_bind_param($stmtDel, "sii", $param_username,$param_sID,$param_dID);
                        $param_username = trim($_POST["status"]);
                        $param_sID = $sID;
                        $param_dID = $dID;
                        if(mysqli_stmt_execute($stmtDel)){
                            header("location: AdminManageApplications.php");
    
                          } else {
                            echo "<script type='text/javascript'>alert('Error updating record.');</script>";
                          }
                        }
                    }
                    
                      
                } else{
                    $name_err = "This Student Not Found.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        }
    }
}
    
   
    
}
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Degree</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = $db->query("SELECT s.name as name, s.email as semail, d.code as degree, a.status as status FROM applications a INNER JOIN student s ON a.student_id = s.id INNER JOIN degree d on a.degree_id = d.id");
                        $count = 1;
                        while($fetch = $query->fetch_array()){
                    ?>
                    <tr>
                        <td><?php echo $count++?></td>
                        <td><?php echo $fetch['name']?></td>
                        <td><?php echo $fetch['semail']?></td>
                        <td><?php echo $fetch['degree']?></td>
                        <td><?php echo $fetch['status']?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <div class="wrapper">
        <h1>Change Application Status</h1>
        <p>Please Select New Application Status.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Applicant Email </label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : '';echo (!empty($aID_err)) ? 'is-invalid' : '';  ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $aID_err; ?></span>
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Degree Code </label>
                <input type="text" name="code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : '';echo (!empty($aID_err)) ? 'is-invalid' : '';  ?>" value="<?php echo $code; ?>">
                <span class="invalid-feedback"><?php echo $aID_err; ?></span>
                <span class="invalid-feedback"><?php echo $code_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Status </label>
                <select name="status">
                    <option value="Submitted">Submitted</option> 
                    <option value="Accepted">Accepted</option> 
                    <option value="Rejected">Rejected</option> 
                </select>
            </div>   
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Remove">
            </div>
            <p>Back to State Dashboard <a href="AdminDashboard.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>


