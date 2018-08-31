
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<form class="form-horizontal" action="" method="post" name="uploadCSV"
    enctype="multipart/form-data">
    <div class="input-row">
        <label class="col-md-4 control-label">Choose CSV File</label> <input
            type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="submit" name="import"
            class="btn-submit">Import</button>
        <br />

    </div>
    <div id="labelError"></div>
</form>


<?php 
//Test Git
//connection with database
$con = mysqli_connect("localhost","root","12345","demo");

// Checking database connection
if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


if (isset($_POST["import"]))
{
    $fileName = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) 
    {

        $file = fopen($fileName, "r");
        $ext = strtolower(end(explode('.', $_FILES['file']['name'])));
        if($ext === 'csv')
        {
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) 
            {
            $sqlInsert = "INSERT into users (userId,userName,email,firstName,lastName)
               values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";

            $result = mysqli_query($con, $sqlInsert);
            //echo print_r($result);die;
            if (! empty($result))
               {
                    $type = "success";
                    $message = "CSV Data Imported into the Database";
                    header("Location: ../listdata.php");
                } else
                {
                    $type = "error";
                    $message = "Problem in Importing CSV Data";
                }
            }
        }
    }
}



?>

</body>
</html>
