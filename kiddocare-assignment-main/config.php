<?php
$con = mysqli_connect("localhost","root","","northwindmysql");

if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL, please try again later." . mysqli_connect_error();
}
?>
