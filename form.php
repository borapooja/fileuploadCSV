<?php 
ini_set('display_errors',1);
function pre($array)
{
    echo "<pre>";
    print_r($array);
}
//phpinfo();
$con = mysqli_connect("localhost","root","12345","demo");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}



if (isset($_POST["import"]))
{
    $file          = $_FILES['file']['tmp_name'];
    $csvArray      = array_map("str_getcsv", file("$file",FILE_SKIP_EMPTY_LINES));           
    $column        = $csvArray[0];
    $columnStr     = implode(',', $column);
    $sql           = array();
    unset($csvArray[0]);  
    foreach($csvArray as $data)
    {
        $sql[] = '("'.($data[0]).'", "'.$data[1].'", "'.$data[2].'", "'.$data[3].'", "'.$data[4].'")';
    }   
    $sqlQuery  = 'INSERT INTO users ('.$columnStr.') VALUES '.implode(',', $sql);
    $result    = mysqli_query($con,$sqlQuery);
    if($result)
    {
        echo 'File Uploaded successfully';
    }
    
 }
 
 if(isset($_POST["Export"]))
 {
    $file          = $_FILES['file']['tmp_name'];
    $csvArray      = array_map("str_getcsv", file("$file",FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
    $delimiter     = ",";
    $filename      = "list_" . date('Y-m-d') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    $array= array_filter(array_map('array_filter', $csvArray));
    $fields = 'email';
    $result_data  = array_flatten($array);
    $unique_data  = array_unique($result_data);
    $mail_data    = test_mail_values($unique_data);
    fputcsv($f, array('email'));
    foreach($mail_data as $row)
    {
       
        fputcsv($f, array($row));
    }
    //move back to beginning of file
    fseek($f, 0);
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
    fpassthru($f);
   
    exit;
 }
 
 
  function test_mail_values($email_id)
  {   
      $search_string    = array("gmail.com","yahoo.com");
      $email_col      = array();
      
      foreach($email_id as  $key => $value)
      { 
        $filter_data = in_array_s($value,$search_string);
        if(($filter_data ==false) && (filter_var($value, FILTER_VALIDATE_EMAIL)))
        {
            $email_col[$key] = $value ;
        }
        
      }
     
      return $email_col;
  }
  
  
 //filter emailids according to domain                 
function in_array_s($value,$search_string)
{
    foreach ($search_string as $sr_item) 
    {
        if (strpos($value,$sr_item)== true)
        {
            return true;
        }
    }

    return false;
}
  
 // for merging arrays 
 function array_flatten($array) { 
  if (!is_array($array)) { 
    return false; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}
?>



<!DOCTYPE html>
<html>
<head>
<style>
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, 
pre, form, fieldset, input, textarea, p, blockquote, th, td { 
  padding:0;
  margin:0;}

fieldset, img {border:0}

ol, ul, li {list-style:none}

:focus {outline:none}

body,
input,
textarea,
select {
  font-family: 'Open Sans', sans-serif;
  font-size: 16px;
  color: #4c4c4c;
}

p {
  font-size: 12px;
  width: 150px;
  display: inline-block;
  margin-left: 18px;
}
h1 {
  font-size: 32px;
  font-weight: 300;
  color: #4c4c4c;
  text-align: center;
  padding-top: 10px;
  margin-bottom: 10px;
}

html{
  background-color: #ffffff;
}

.testbox {
  margin: 20px auto;
  width: 343px; 
  height: 464px; 
  -webkit-border-radius: 8px/7px; 
  -moz-border-radius: 8px/7px; 
  border-radius: 8px/7px; 
  background-color: #ebebeb; 
  -webkit-box-shadow: 1px 2px 5px rgba(0,0,0,.31); 
  -moz-box-shadow: 1px 2px 5px rgba(0,0,0,.31); 
  box-shadow: 1px 2px 5px rgba(0,0,0,.31); 
  border: solid 1px #cbc9c9;
}

input[type=radio] {
  visibility: hidden;
}

form{
  margin: 0 30px;
}

label.radio {
	cursor: pointer;
  text-indent: 35px;
  overflow: visible;
  display: inline-block;
  position: relative;
  margin-bottom: 15px;
}

label.radio:before {
  background: #3a57af;
  content:'';
  position: absolute;
  top:2px;
  left: 0;
  width: 20px;
  height: 20px;
  border-radius: 100%;
}

label.radio:after {
	opacity: 0;
	content: '';
	position: absolute;
	width: 0.5em;
	height: 0.25em;
	background: transparent;
	top: 7.5px;
	left: 4.5px;
	border: 3px solid #ffffff;
	border-top: none;
	border-right: none;

	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

input[type=radio]:checked + label:after {
	opacity: 1;
}

hr{
  color: #a9a9a9;
  opacity: 0.3;
}

input[type=text],input[type=password]{
  width: 200px; 
  height: 39px; 
  -webkit-border-radius: 0px 4px 4px 0px/5px 5px 4px 4px; 
  -moz-border-radius: 0px 4px 4px 0px/0px 0px 4px 4px; 
  border-radius: 0px 4px 4px 0px/5px 5px 4px 4px; 
  background-color: #fff; 
  -webkit-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  -moz-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  border: solid 1px #cbc9c9;
  margin-left: -5px;
  margin-top: 13px; 
  padding-left: 10px;
}

input[type=password]{
  margin-bottom: 25px;
}

#icon {
  display: inline-block;
  width: 30px;
  background-color: #3a57af;
  padding: 8px 0px 8px 15px;
  margin-left: 15px;
  -webkit-border-radius: 4px 0px 0px 4px; 
  -moz-border-radius: 4px 0px 0px 4px; 
  border-radius: 4px 0px 0px 4px;
  color: white;
  -webkit-box-shadow: 1px 2px 5px rgba(0,0,0,.09);
  -moz-box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  box-shadow: 1px 2px 5px rgba(0,0,0,.09); 
  border: solid 0px #cbc9c9;
}

.gender {
  margin-left: 30px;
  margin-bottom: 30px;
}

.accounttype{
  margin-left: 8px;
  margin-top: 20px;
}

a.button {
  font-size: 14px;
  font-weight: 600;
  color: white;
  padding: 6px 25px 0px 20px;
  margin: 10px 8px 20px 0px;
  display: inline-block;
  float: right;
  text-decoration: none;
  width: 50px; height: 27px; 
  -webkit-border-radius: 5px; 
  -moz-border-radius: 5px; 
  border-radius: 5px; 
  background-color: #3a57af; 
  -webkit-box-shadow: 0 3px rgba(58,87,175,.75); 
  -moz-box-shadow: 0 3px rgba(58,87,175,.75); 
  box-shadow: 0 3px rgba(58,87,175,.75);
  transition: all 0.1s linear 0s; 
  top: 0px;
  position: relative;
}

a.button:hover {
  top: 3px;
  background-color:#2e458b;
  -webkit-box-shadow: none; 
  -moz-box-shadow: none; 
  box-shadow: none;
  
}


</style>
</head>
<body>


<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600' rel='stylesheet' type='text/css'>
<link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">

<div class="testbox" style="width: 747px !important;">
  <h1> Upload Csv File</h1>

  <form class="form-horizontal" action="" method="post" name="uploadCSV"
    enctype="multipart/form-data">
      <div id="labelSuccess"><?php //echo $message ;?></div>
    <div class="input-row">
        <label class="col-md-4 control-label">Choose CSV File : </label><br/><br/>
         <input type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="submit" name="import"
            class="btn-submit">Import</button>
        <br />
    </div> <br />
      <div class="input-row">
        <label class="col-md-4 control-label">Export/Import CSV File : </label><br/><br/>
         <input type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="Export" name="Export"
            class="btn-submit">Export/Import</button>
        <br />
          
    </div>
    <div id="labelError"><?php //echo $message ?></div>
</form>
</div>



</body>
</html> <!-- /form -->
