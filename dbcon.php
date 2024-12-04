<?php
$conn = mysqli_connect('13.58.47.110','root','','myweb');
if(!$conn){
    die("Cannot connect to the database. Error: ".mysqli_error($conn));
}
?>