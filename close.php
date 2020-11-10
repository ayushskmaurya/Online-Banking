<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "close.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['pin'])) {
		$stmt = $conn->prepare("SELECT pin FROM mvmt_records WHERE accountNo = :ac_no");
		$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!is_numeric($_POST['pin']) || strlen($_POST['pin']) != 4)
			$_SESSION['error'] = "Invalid PIN";
		elseif($row['pin'] !== $_POST['pin'])
			$_SESSION['error'] = "Incorrect PIN";
		else {
			$stmt = $conn->prepare("DELETE FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
			$stmt = $conn->prepare("DELETE FROM mvmt_statements WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));

			session_unset();
			$_SESSION['logout'] = "Account deleted successfully.";
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
		<link rel="stylesheet" href="styles/close-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="close">Close Account</h2>

				<form method="POST">
					<label class="close">Enter PIN</label><br>
					<input class="close" type="password" name="pin" placeholder="Enter PIN"><br>
					<input class="b-close" type="submit" value="Confirm">
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
