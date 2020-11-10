<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "aadhaar.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['aadhaar'])) {
		if(!is_numeric($_POST['aadhaar']) || strlen($_POST['aadhaar']) != 12)
			$_SESSION['error'] = "Invalid Aadhaar No.";
		else {
			$stmt = $conn->prepare("UPDATE mvmt_records SET aadhaar= :aadhaar WHERE accountNo = :ac_no");
			$stmt->execute(array(
				":aadhaar" => $_POST['aadhaar'],
				":ac_no" => $_SESSION['ac_no']
			));

			$_SESSION['logout'] = "Aadhaar Card linked successfully.";
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
		<link rel="stylesheet" href="styles/aadhaar-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="aadhaar">Link Aadhaar Card</h2>

				<form method="POST">
					<label class="aadhaar">Aadhaar No</label><br>
					<input class="aadhaar" type="text" name="aadhaar" placeholder="Enter No."><br>
					<input class="b-aadhaar" type="submit" value="Link">
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
