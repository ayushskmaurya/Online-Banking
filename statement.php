<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "statement.php";

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
		<link rel="stylesheet" href="styles/statement-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php
			include "header.php";

			echo "<h2 class='statement'>Account Statement</h2>";

			$stmt = $conn->prepare("SELECT * FROM mvmt_statements WHERE accountNo = :ac_no ORDER BY dateTime DESC");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));

			echo "<table>";
			echo "<tr class='header'>";
			echo "<th>Description</th>";
			echo "<th>Date-Time</th>";
			echo "<th>Amount</th>";
			echo "</tr>";
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo "<tr class='row'><td>";
					echo htmlentities($row['info']);
					echo "</td><td>";
					echo htmlentities($row['dateTime']);
					echo "</td><td class='amt'>";
					echo htmlentities($row['amount']);
					echo "</td></tr>";
				}
			echo "</table>";

			include "footer.html";
		?>
	</body>
</html>
