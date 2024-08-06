<?php

    $db= new mysqli("localhost","root","","edoc");
    if ($db->connect_error){
        die("Connection failed:  ".$db->connect_error);
    }

?>
    

