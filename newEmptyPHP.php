<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$file = $_FILES['file']['tmp_name'];
            $csv = array_map("str_getcsv", file("$file",FILE_SKIP_EMPTY_LINES));
            $keys = array_shift($csv);
            for($i=0;$i<=sizeof($keys);$i++)
            {
                $name=$keys[$i];
                if($name=="xyz") {
                    $xyz = $i;
                    continue;
                }
               else if($name=="abc") {
                    $abc = $i;
                    continue;
                }
              else if($name=="tax") {
                    $tax = $i;
                    continue;
                }
}

if ((isset($_POST["import"]))) {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");
        $c = 0;
        $branch=$_POST["import"];

        while (($filesop = fgetcsv($handle, 3000000, ",")) !== false) {
            $xyz = $filesop[$xyz];
            $abc = $filesop[$abc];

            $freaight2 = substr($abc, 0, -2);
            if($freaight2=="")
            {
                $freaight2=0;
            }
            $with_tax = $filesop[$tax];
            $with_tax2 = substr($with_tax, 0, -2);
            $Invoice_Number = $branch."_" .$invoice_number;
            $update_freaight = "UPDATE xyz SET `Total_Freight`=" . $freaight2 . ",`Value_With_Tax`='" . $with_tax2 . "' where `Invoice_Number`='" . $Invoice_Number . "'";
          //   echo $update_freaight;
            $update = mysqli_query($con, $update_freaight);
        }
        if ($update) {
            $massage = "You database has imported successfully. You have inserted " . $c . " recoreds";
        } else {
            $massage = "Sorry! There is some problem.";
        }

    }
    
    ?>