<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "deposit.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['deposit'])) {
		if(!is_numeric($_POST['deposit']))
			$_SESSION['error'] = "Invalid Amount";
		else {

			$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :bal WHERE accountNo = :ac_no");
			$stmt->execute(array(
				":bal" => $row['balancedAmount'] + $_POST['deposit'],
				":ac_no" => $_SESSION['ac_no']
			));

			$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:ac_no, :detail, now(), :amt)");
			$stmt->execute(array(
				":ac_no" => $_SESSION['ac_no'],
				":detail" => "Deposited",
				":amt" => $_POST['deposit']
			));

			$_SESSION['logout'] = "Money deposited successfully.";
			header("Location: index.php");
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MVMT Bank</title>
		<link rel="icon" href="files/Logo.jpg">
		<link rel="stylesheet" href="styles/deposit-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="deposit">Deposit Money</h2>

				<form method="POST">
					<label class="deposit">Amount</label><br>
					<input class="deposit" type="text" name="deposit" placeholder="Enter Amount"><br>
					<input class="b-deposit" type="submit" value="Deposit">
				</form>

				<?php
					if(isset($_SESSION['error'])) {
						echo "<p class='error'>".$_SESSION['error']."</p>";
						unset($_SESSION['error']);
					}
				?>

		</div>
		<?php include "footer.html"; ?>
	</body>
</html>
