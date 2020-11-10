<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "btransfer.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['btransfer'])) {

		if(!is_numeric($_POST['rac_no']) || strlen($_POST['rac_no']) != 12)
			$_SESSION['error'] = "Invalid Account No.";
		else {

			$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :rac_no");
			$stmt->execute(array(":rac_no" => $_POST['rac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 0)
					$_SESSION['error'] = "Account doesn't exists.";

			elseif(!is_numeric($_POST['btransfer']))
				$_SESSION['error'] = "Invalid Amount";

			else {

				$rbal = $row['balancedAmount'];
				$stmt = $conn->prepare("SELECT balancedAmount FROM mvmt_records WHERE accountNo = :ac_no");
				$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row['balancedAmount'] < $_POST['btransfer'])
					$_SESSION['error'] = "Amount exceeds your balanced amount.";

				else {

					$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :bal WHERE accountNo = :ac_no");
					$stmt->execute(array(
						":bal" => $row['balancedAmount'] - $_POST['btransfer'],
						":ac_no" => $_SESSION['ac_no']
					));

					$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:ac_no, :detail, now(), :amt)");
					$stmt->execute(array(
						":ac_no" => $_SESSION['ac_no'],
						":detail" => "Debited (A/C: ".$_POST['rac_no'].")",
						":amt" => -$_POST['btransfer']
					));

					$stmt = $conn->prepare("UPDATE mvmt_records SET balancedAmount= :rbal WHERE accountNo = :rac_no");
					$stmt->execute(array(
						":rbal" => $rbal + $_POST['btransfer'],
						":rac_no" => $_POST['rac_no']
					));

					$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES (:rac_no, :detail, now(), :amt)");
					$stmt->execute(array(
						":rac_no" => $_POST['rac_no'],
						":detail" => "Credited (A/C: ".$_SESSION['ac_no'].")",
						":amt" => $_POST['btransfer']
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
		<link rel="stylesheet" href="styles/btransfer-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="btransfer">Transfer Money</h2>

				<form method="POST">
					<label class="btransfer">Reciever's Account No.</label><br>
					<input class="btransfer" type="text" name="rac_no" placeholder="Enter A/C No."><br>
					<label class="btransfer">Amount</label><br>
					<input class="btransfer" type="text" name="btransfer" placeholder="Enter Amount"><br>
					<input class="b-btransfer" type="submit" value="Transfer">
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
