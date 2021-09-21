<?php
require("config.php");
include("auth.php");

 $totalsales;
 $totalorders;
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Dashboard</title>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <link rel="stylesheet" href="css/main.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
<body>

    <div class="line"></div>
        <div class="topnav">
          <a href="logout.php">Logout</a>
        </div>

        <h2> My Dashboard </h2>
      <main>
<div class='content'>
          <?php
          $query=mysqli_query($con,"select ROUND(SUM((UnitPrice*Quantity-Discount)),2) as 'totalsales' from order_details
INNER JOIN orders ON order_details.OrderID = orders.OrderID where orders.ShippedDate
between date('1995-05-01') and date('1995-05-31');")
          or die (mysqli_error($con));
          while ($row=mysqli_fetch_array($query)) {
          ?>
          <div class="outline">
          <table>
            <tr>
            <th>Total Sales</th>
          </tr>
          <tr>
            <td><?php echo $row['totalsales'];?></td>
          </tr>
       <?php
}?>
        </table>
    </div>

    <?php
    $query=mysqli_query($con,"select COUNT(ShippedDate) as 'totalorders' from orders where ShippedDate between date('1995-05-01') and date('1995-05-31');")
    or die (mysqli_error($con));
    while ($row=mysqli_fetch_array($query)) {
    ?>
    <div class="outline">
    <table>
      <tr>
      <th>Total Orders</th>
    </tr>
    <tr>
      <td><?php echo $row['totalorders'];?></td>
    </tr>
 <?php
}?>
  </table>
</div>
</div>
<br>
<br>

<div class="content">
<div class="outlinegraph">
  <script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Date','Daily Sales'],
  <?php
  $query=mysqli_query($con, "select ShippedDate, count(*) as Sales from orders where ShippedDate between '1995-05-01' and '1995-05-31'
  group by ShippedDate order by ShippedDate;")
  or die (mysqli_error($con));
  while ($row=mysqli_fetch_array($query)) {
echo "['".$row['ShippedDate']."',".$row['Sales']."],";
}
      ?>

]);
var options = {
 title: 'Total Sales for May 1995',
 colors: ['maroon']
 };
 var chart = new google.visualization.ColumnChart(document.getElementById("dailysalesbar"));
 chart.draw(data, options);
 }
    </script>
    <div class="container-fluid">
     <div id="dailysalesbar" style="height: 700px; width: 100%;"></div>
     </div>
<br>
<br>
</div>
</div>

<div class="content">
<div class="outlinegraph">
  <script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
  var data = google.visualization.arrayToDataTable([

  ['Category','Percentage'],
  <?php
  $query=mysqli_query($con, "select CategoryName, round(sum(ProductSales), 2) as CategorySales from ( select distinct a.CategoryName, b.ProductName, round(sum(c.UnitPrice * c.Quantity * (1 - c.Discount)), 2) as ProductSales from Categories as a inner join Products as b on a.CategoryID = b.CategoryID inner join Order_Details as c on b.ProductID = c.ProductID inner join Orders as d on d.OrderID = c.OrderID where d.ShippedDate between '1995-05-01' and '1995-05-31' group by a.CategoryName, b.ProductName order by a.CategoryName, b.ProductName) as CategorySales group by CategoryName order by CategoryName;")
  or die (mysqli_error($con));
  while ($row=mysqli_fetch_array($query)) {
echo "['".$row['CategoryName']."',".$row['CategorySales']."],";
}
      ?>

]);
var options = {
 title: 'Percentage of sales by product categories for May 1995',
  is3D: true,
 };
 var chart = new google.visualization.PieChart(document.getElementById("categorypercentage"));
 chart.draw(data,options);
 }
    </script>
    <div class="container-fluid">
     <div id="categorypercentage" style="height: 700px; width: 100%;"></div>
     </div>
<br>
<br>
</div>
</div>
<div class="content">
<div class="outlinegraph">
  <script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Company Name','No. Of Customer Sales'],
  <?php
  $query=mysqli_query($con, "select CompanyName, count(o.CustomerID) as NoOfSales
from orders as o inner join customers as c on o.CustomerID = c.CustomerID
where o.ShippedDate between '1995-05-01' and '1995-05-31'
GROUP BY CompanyName
ORDER BY NoOfSales DESC;")
  or die (mysqli_error($con));
  while ($row=mysqli_fetch_array($query)) {
echo "['".$row['CompanyName']."',".$row['NoOfSales']."],";
}
      ?>

]);
var options = {
 title: 'Number of Sales by Customers for May 1995',
 };
 var chart = new google.visualization.BarChart(document.getElementById("customersales"));
 chart.draw(data, options);
 }
    </script>
    <div class="container-fluid">
     <div id="customersales" style="height: 700px; width: 100%;"></div>
     </div>
<br>
<br>
</div>
</div>

<div class="content">
<div class="outlinegraph">
  <script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Name','No. Of Employee Sales'],
  <?php
  $query=mysqli_query($con, "SELECT CONCAT(FirstName , ' ' , Lastname)
AS Name, COUNT(O.OrderID) AS NoOfSales
FROM employees AS E
INNER JOIN orders AS O ON (E.EmployeeID = O.EmployeeID)
INNER JOIN order_details AS D ON (O.OrderID = D.OrderID)
where O.ShippedDate between '1995-05-01' and '1995-05-31'
GROUP BY E.EmployeeID
ORDER BY NoOfSales DESC;")
  or die (mysqli_error($con));
  while ($row=mysqli_fetch_array($query)) {
echo "['".$row['Name']."',".$row['NoOfSales']."],";
}
      ?>

]);
var options = {
 title: 'Number of Sales by Employees for May 1995',
 colors: ['maroon']
 };
 var chart = new google.visualization.BarChart(document.getElementById("employeesales"));
 chart.draw(data, options);
 }
    </script>
    <div class="container-fluid">
     <div id="employeesales" style="height: 700px; width: 100%;"></div>
     </div>
<br>
<br>
</div>
</div>

    <div class="wrapfooter">
<footer> Powered by YZ Designs </footer>
    </div>
    </body>
    </html>
