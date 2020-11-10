<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "ptransfer.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['ptransfer'])) {

		if (!is_numeric($_POST['mob']) || strlen($_POST['mob']) != 10)
			$_SESSION['error'] = "Invalid Mobile No.";
		else {

			$stmt = $conn->prepare("SELECT accountNo, balancedAmount FROM mvmt_records WHERE mobile = :mob");
			$stmt->execute(array(":mob" => $_POST['mob']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 0)
					$_SESSION['error'] = "Account doesn't exists.";

			elseif(!is_numeric($_POST['ptransfer']))
				$_SESSION['error'] = "Invalid Amount";

			else {

				$rac_no = $row['accountNo'];
				$rbal = $row['balancedAmount'];
				$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :ac_no");
				$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row['balancedAmount'] < $_POST['ptransfer'])
					$_SESSION['error'] = "Amount exceeds your balanced amount.";

				else {

					$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :bal WHERE accountNo = :ac_no");
					$stmt->execute(array(
						":bal" => $row['balancedAmount'] - $_POST['ptransfer'],
						":ac_no" => $_SESSION['ac_no']
					));

					$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:ac_no, :detail, now(), :amt)");
					$stmt->execute(array(
						":ac_no" => $_SESSION['ac_no'],
						":detail" => "Debited (A/C: ".$rac_no.")",
						":amt" => -$_POST['ptransfer']
					));

					$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :rbal WHERE mobile = :mob");
					$stmt->execute(array(
						":rbal" => $rbal + $_POST['ptransfer'],
						":mob" => $_POST['mob']
					));

					$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:rac_no, :detail, now(), :amt)");
					$stmt->execute(array(
						":rac_no" => $rac_no,
						":detail" => "Credited (A/C: ".$_SESSION['ac_no'].")",
						":amt" => $_POST['ptransfer']
					));

					$_SESSION['logout'] = "Money transferred successfully.";
					header("Location: index.php");
				}
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
		<link rel="stylesheet" href="styles/ptransfer-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="ptransfer">Transfer Money</h2>

				<form method="POST">
					<label class="ptransfer">Reciever's Mobile No.</label><br>
					<input class="ptransfer" type="text" name="mob" placeholder="Enter Mobile No."><br>
					<label class="ptransfer">Amount</label><br>
					<input class="ptransfer" type="text" name="ptransfer" placeholder="Enter Amount"><br>
					<input class="b-ptransfer" type="submit" value="Transfer">
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
