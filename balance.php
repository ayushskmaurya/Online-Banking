<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "balance.php";

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
		<link rel="stylesheet" href="styles/balance-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php
			include "header.php";

			$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			echo "<table><tr>";
				echo "<td class='bal'>Balanced Amount</td>";
				echo "<td class='amt'>₹".htmlentities($row['balancedAmount'])."</td>";
			echo "</tr></table>";

			include "footer.html";
			?>
	</body>
</html>
