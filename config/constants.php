<?php 





   //3.execute query and save data in data base
            //uda 2 code eka dammha wena de
            $conn = mysqli_connect('localhost','root','') or die(mysqli_error()); // database connection eka  connet wenna bari unoth (die eka wenawa).
           $db_select = mysqli_select_db($conn,'food-order') or die (mysqli_error());// data base eka teruwa nama dila

?>