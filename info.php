<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "info.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MVMT Bank</title>
		<link rel="icon" href="files/Logo.jpg">
		<link rel="stylesheet" href="styles/info-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php
			include "header.php";

			$stmt = $conn->prepare("SELECT * FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			echo "<table>";
				echo "<tr class='header'><th colspan='2'>User Information</th></tr>";

				echo "<tr class='row'><td>Account No</td><td>";
				echo htmlentities($row['accountNo']);

				echo "</td></tr><tr class='row'><td>First Name</td><td>";
				echo htmlentities($row['firstName']);

				echo "</td></tr><tr class='row'><td>Last Name</td><td>";
				echo htmlentities($row['lastName']);

				echo "</td></tr><tr class='row'><td>Balanced Amount</td><td class='amt'>";
				echo "₹".htmlentities($row['balancedAmount']);

				echo "</td></tr><tr class='row'><td>Aadhaar No.</td><td>";
				echo htmlentities($row['aadhaar']);

				echo "</td></tr><tr class='row'><td>Mobile No.</td><td>";
				echo htmlentities($row['mobile']);

				echo "</td></tr><tr class='row'><td>Email</td><td>";
				echo htmlentities($row['email']);

				echo "</td></tr>";
			echo "</table>";

			include "footer.html";
		?>
	</body>
</html>
