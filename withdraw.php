<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "withdraw.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['withdraw'])) {
		if(!is_numeric($_POST['withdraw']))
			$_SESSION['error'] = "Invalid Amount";
		else {

			$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($row['balancedAmount'] < $_POST['withdraw'])
				$_SESSION['error'] = "Withdrawal amount exceeds your balanced amount.";

			else {

				$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :bal WHERE accountNo = :ac_no");
				$stmt->execute(array(
					":bal" => $row['balancedAmount'] - $_POST['withdraw'],
					":ac_no" => $_SESSION['ac_no']
				));

				$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:ac_no, :detail, now(), :amt)");
				$stmt->execute(array(
					":ac_no" => $_SESSION['ac_no'],
					":detail" => "Withdrawn",
					":amt" => -$_POST['withdraw']
				));

				$_SESSION['logout'] = "Money withdrawn successfully.";
				header("Location: index.php");
			}
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
		<link rel="stylesheet" href="styles/withdraw-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="withdraw">Withdraw Money</h2>

				<form method="POST">
					<label class="withdraw">Amount</label><br>
					<input class="withdraw" type="text" name="withdraw" placeholder="Enter Amount"><br>
					<input class="b-withdraw" type="submit" value="Withdraw">
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
