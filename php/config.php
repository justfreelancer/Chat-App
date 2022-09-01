<?php
    $conn = mysqli_connect("localhost", "root", "", "chatweb");
    if(!$conn){
        echo "Database connected" . mysqli_connect_error;
    }
?>
