<?php
require_once "config.php";
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ..\AccountSystem1\login.php");
    exit;
}

	$user_name = $_SESSION["username"];
	$user_name_leng = strlen($user_name);
	    if ($user_name_leng <= 5) {
	      	$hour = 200;
	    }else{
	    	$hour = 150;
	    }

	$sql = "SELECT SUM(TotalPrice) AS Commission, employeename from orderlist_s where employeename = '$user_name'";
	$result = $link->query($sql);
	    if($result->num_rows > 0){
	        while ($row = $result->fetch_assoc()) {
	        	$subcomm = 0;
	        	$subcomm = $row['Commission'];
	        }
	    }
	    $comm = $subcomm * (0.5/100);

	$sql = "SELECT COUNT(username) AS Count from attendance where username = '$user_name'";
	$result = $link->query($sql);
	if($result->num_rows > 0){
	        while ($row = $result->fetch_assoc()) {
	        	$countname = $row['Count'];
	        }
	    }

	$salary = $hour * $countname;

	if ($countname >= 25) {
		$PAA = 100;
	}else{
		$PAA = 0;
	}

	$Subsalary = $salary+$comm+$PAA;

	$AftEPf = $Subsalary * (11/100);
	$EPF = number_format($AftEPf, 2);
	$totalSalary = $Subsalary - $EPF;
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Employee Salary</title>
	<link href="../mario.png" rel="icon">
	<script src="script.js"></script>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidenav">
  <a href="..\AccountSystem1\welcome.php"><p id="p1"><img src="icon\account.png" width="20" height="20" style="padding-right: 10px;"><strong><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></strong></p></a>
  <a class="active" href="..\AccountSystem1\welcome.php"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
  <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
  </svg> Home</a>
  <a href="..\HomePages\Home.php"><img src="icon\company.png" width="20" height="20"> Company Profile</a>
  <a href="..\CheckIn\check.php"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9.854-2.854a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
  </svg> Attendance</a>
  <a href="..\Salary\calculate.php"><img src="icon\money.png" width="20" height="20"> Salary</a>
  <button class="dropdown-btn" onclick="dropdown_btn()" > 
    Email<img src="icon\dropdown.png" width="10" height="10" style="padding: 0px 0px 0px 110px;">
  </button>
  <div class="dropdown-container">
    <a href="..\Email\ReadEmail.php"><img src="icon\Email.png" width="20" height="20"> Email</a>
    <a href="..\Email\write.php"><img src="icon\email2.png" width="20" height="20"> Write Email</a>
  </div>
  <?php
    $user_name = $_SESSION["username"];
    $user_name_leng = strlen($user_name);
    if ($user_name_leng <= 5) {
      echo "<a href='..\leave\approve.php'><img src='icon\Leave.png' width='20' height='20'> Aprove leave</a>";
      echo "<a href='..\Resume\Frist.php'><img src='icon\Leave.png' width='20' height='20'> Recruitment Employee</a>";
    }
  ?>
  <a href="..\leave\leaveapplication.php"><img src="icon\Leave.png" width="20" height="20"> Leave</a>
  <a href="..\AccountSystem1\reset-password.php"><img src="icon\settingsw.png" width="20" height="20"> Settings</a>
  <a href="..\AccountSystem1\logout.php"><img src="icon\logout.png" width="20" height="20"> Log Out</a>
</div>
	<form action="calculate.php" method="POST">
		<table>
			<caption>Salary</caption>
			<tr>
				<td>Employee Name: </td>
				<td class="num"> <?php echo htmlspecialchars($_SESSION["username"]); ?></td>
			</tr>
			<tr>
				<td>Salary: </td>
				<td class="num" style="width:500px">RM <?php echo $hour; ?> (per day) * <?php echo $countname; ?> = RM <?php echo $salary; ?></td>
			</tr>
			<tr>
				<td>Sales Commission: </td>
				<td class="num">RM <?php echo $comm; ?></td>
			</tr>
			<tr>
				<td>Perfect Attendance Award<br>(If you work for more than 25 days)</td>
				<td class="num">RM <?php echo $PAA; ?></td>
			</tr>
			<tr>
				<td>Sub total</td>
				<td class="num">RM <?php echo $Subsalary; ?></td>
			</tr>
			<tr>
				<td>EPF Socso: </td>
				<td class="num">- Sub Total Salary * 11% = RM <?php echo $EPF; ?></td>
			</tr>
			<tr>
				<td>Total Salary:</td>
				<td class="num">RM <?php echo $totalSalary; ?></td>
			</tr>
		</table>
	</form>
</body>
</html>