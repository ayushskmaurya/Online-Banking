<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "pin.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['pin'])) {
		$stmt = $conn->prepare("SELECT pin FROM mvmt_records WHERE accountNo = :ac_no");
		$stmt->execute(array(":ac_no" => $_SESSION['ac_no']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($row['pin'] !== $_POST['opin'])
			$_SESSION['error'] = "Incorrect PIN";
		elseif (!is_numeric($_POST['pin']) || strlen($_POST['pin']) != 4)
			$_SESSION['error'] = "Invalid PIN";
		elseif ($_POST['pin'] !== $_POST['rpin'])
			$_SESSION['error'] = "PINs entered are not same";
		else {
			$stmt = $conn->prepare("UPDATE mvmt_records SET pin= :pin WHERE accountNo = :ac_no");
			$stmt->execute(array(
				":pin" => $_POST['pin'],
				":ac_no" => $_SESSION['ac_no']
			));

			$_SESSION['logout'] = "PIN changed successfully.";
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
		<link rel="stylesheet" href="styles/pin-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="pin">Change PIN</h2>

				<form method="POST">
					<label class="pin">Enter Old PIN</label><br>
					<input class="pin" type="password" name="opin" placeholder="Enter PIN"><br>
					<label class="pin">Enter New PIN</label><br>
					<input class="pin" type="password" name="pin" placeholder="Enter PIN"><br>
					<label class="pin">Re-enter New PIN</label><br>
					<input class="pin" type="password" name="rpin" placeholder="Enter PIN"><br>
					<input class="b-pin" type="submit" value="Update">
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
