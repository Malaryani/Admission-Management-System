<?php
// Include config file
include("config.php");
session_start();
// Define variables and initialize with empty values
$name = trim($_SESSION['login_user']);
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
    }else{
        $name  = trim($_SESSION['login_user']);
        $query = $db->query("SELECT id FROM degree WHERE code = '".trim($_POST["code"])."'");
        while($fetch = $query->fetch_array()){
            $dID = $fetch['id'];
        }
        if($dID == -1){
            $code_err = "Please enter valid code.";     
        }
        else{
        // Prepare a select statement
        $sql = "SELECT id FROM student WHERE email = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){

                    $query = $db->query("SELECT id FROM student WHERE email = '".$param_username."'");
                    while($fetch = $query->fetch_array()){
                        $sID = $fetch['id'];
                    }
                    
                        $delSql = "INSERT INTO applications (student_id,degree_id,status) VALUES (?,?,?)";
                        if($stmtDel = mysqli_prepare($db, $delSql)){
    
                        mysqli_stmt_bind_param($stmtDel, "iis",$param_sID,$param_dID,$param_status);
                        $param_sID = $sID;
                        $param_dID = $dID;
                        $param_status  = "Submitted";
                        if(mysqli_stmt_execute($stmtDel)){
                            header("location: StudentPortalDashboard.php");
    
                          } else {
                            echo "<script type='text/javascript'>alert('Error updating record.');</script>";
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
                        <th>Degree Name</th>
                        <th>Degree Code</th>
                        <th>Degree Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
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
        </div>
    <div class="wrapper">
        <h1>Create New Application</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
             
            <div class="form-group">
                <label>Degree Code </label>
                <select name="code">
                    <?php
                        $query = $db->query("SELECT * FROM degree");
                        $count = 1;
                        while($fetch = $query->fetch_array()){
                    ?>
                    <option value=<?php echo $fetch['code']?>><?php echo $fetch['code']?></option> 
                    <?php
                        }

                    ?>
                </select>
            </div>   
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create Application">
            </div>
            <p>Back to Student Portal Login <a href="logout.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>


