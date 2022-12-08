<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$code = "";
$code_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
        // Prepare a select statement
        $sql = "SELECT id FROM degree WHERE code = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["code"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $code = trim($_POST["code"]);
                    $delSql = "DELETE FROM degree WHERE code = ?";
                    if($stmtDel = mysqli_prepare($db, $delSql)){

                    mysqli_stmt_bind_param($stmtDel, "s", $param_username);
                    $param_username = $code;
                    if(mysqli_stmt_execute($stmtDel)){
                        header("location: AdminManageDegree.php");

                      } else {
                        echo "<script type='text/javascript'>alert('Error deleting record.');</script>";
                      }
                    }
                      
                } else{
                    $code_err = "This email is not found.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    
    
   
    
    // Close connection
    mysqli_close($db);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove</title>
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
        
        <h1>Degree List</h1>
            <br />
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Duration</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = $db->query("SELECT * FROM `degree` ORDER BY `id` ASC");
                        $count = 1;
                        while($fetch = $query->fetch_array()){
                    ?>
                    <tr>
                        <td><?php echo $count++?></td>
                        <td><?php echo $fetch['name']?></td>
                        <td><?php echo $fetch['duration']?></td>
                        <td><?php echo $fetch['code']?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <div class="wrapper">
        <h1>Remove Degree</h1>
        <p>Please Enter Code of the degree to remove.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Degree Code </label>
                <input type="text" name="code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $code; ?>">
                <span class="invalid-feedback"><?php echo $code_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Remove">
            </div>
            <p>Back to Degree Dashboard <a href="AdminManageDegree.php">Click Here</a>.</p>
        </form>
    </div>    
    </div>    
</body>
</html>


