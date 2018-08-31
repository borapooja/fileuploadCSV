<?php 
ini_set('display_errors',1);
function pre($array)
{
    echo "<pre>";
    print_r($array);
}
$con = mysqli_connect("localhost","root","12345","demo");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
$sqlSelect = "SELECT * FROM users";
$result = mysqli_query($con, $sqlSelect);
            
 if(isset($_POST["Export"]))
 {
     
    $query = mysqli_query($con, $sqlSelect);
    if($query->num_rows > 0)
    {
        $delimiter = ",";
        $filename = "list_" . date('Y-m-d') . ".csv";
        //create a file pointer
        $f = fopen('php://memory', 'w');
        //set column headers
        $fields = array('user_id','first_name', 'last_name', 'email','phone_no');
        fputcsv($f, $fields, $delimiter);
        //output each row of the data, format line as csv and write to file pointer
        while($row = $query->fetch_assoc())
        {
        
            $lineData = array($row['user_id'],$row['first_name'], $row['last_name'], $row['email'], $row['phone_no']);
            fputcsv($f, $lineData, $delimiter);
        }
        //move back to beginning of file
        fseek($f, 0);
        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        //output all remaining data on a file pointer
        fpassthru($f);
    }
    exit;
	
//      header('Content-Type: text/csv; charset=utf-8');  
//      header('Content-Disposition: attachment; filename=data.csv');  
//      $output = fopen("php://memory", "w");
//      
//      fputcsv($output, array('user_id', 'user_name', 'first_name', 'last_name', 'email'));  
//      $query = "SELECT * from users ORDER BY id DESC";  
//      //$result = mysqli_query($con, $query);  
//      while($row = mysqli_fetch_assoc($result,MYSQLI_BOTH))  
//      {  
//           $data = fputcsv($output, $row); 
//           
//      }  
//      fclose($output);  
 }  
 
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php


?>
<div class="container">
  

<table id='userTable' class="table table-striped">
    <thead>
        <tr>
            <th>S.No. </th>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone Number</th>
            <th>Email</th>

        </tr>
    </thead>
    <?php
    if (mysqli_num_rows($result) > 0) {
        $i=1;
	while ($row = mysqli_fetch_array($result,MYSQLI_BOTH)) {
    ?>

    <tbody>
        <tr>
            <td><?php  echo $i; ?></td>
            <td><?php  echo $row['user_id']; ?></td>
            <td><?php  echo $row['first_name']; ?></td>
            <td><?php  echo $row['last_name']; ?></td>
            <td><?php  echo $row['phone_no']; ?></td>
            <td><?php  echo $row['email']; ?></td>
        </tr>
     <?php
    $i++; }
      } else { ?>
        <tr>
            <td colspan="6">
                <center>No Record Found !! </center>
            </td>
        </tr>
      <?php } ?>
 </tbody>
</table>
     <form class="form-horizontal"  method="post" name="upload_excel"   
                      enctype="multipart/form-data">
<input type="submit" name="Export" class="pull-right btn btn-success" align="right" value="export to excel"/>
   </form>
</div>
</div>
</body>
</html>
