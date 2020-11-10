<?php
	include "connection.php";
	session_start();

	if(isset($_SESSION['login']))
		header("Location:".$_SESSION['page']);

	if(isset($_POST['ac_no'])) {

		if(!is_numeric($_POST['ac_no']) || strlen($_POST['ac_no']) != 12)
			$_SESSION['error'] = "Invalid Account No.";
		elseif (!is_numeric($_POST['pin']) || strlen($_POST['pin']) != 4)
			$_SESSION['error'] = "Invalid PIN";
		
		else {

			$stmt = $conn->prepare("SELECT accountNo, firstName, lastName, pin FROM mvmt_records WHERE accountNo = :ac_no");
			$stmt->execute(array(":ac_no" => $_POST['ac_no']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 0)
				$_SESSION['error'] = "Account doesn't exists.";
			elseif($_POST['pin'] !== $row['pin'])
				$_SESSION['error'] = "Wrong PIN.";
			else {
				$_SESSION['ac_no'] = $_POST['ac_no'];
				$_SESSION['login'] = "Welcome ".htmlentities($row['firstName'])." ".htmlentities($row['lastName'])."!";
				header("Location:".$_SESSION['page']);
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
		<link rel="stylesheet" href="styles/login-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="login">Login</h2>

				<form method="POST">
					<label class="login">Account No.</label><br>
					<input class="login" type="text" name="ac_no" placeholder="Enter A/C No."><br>
					<label class="login">PIN</label><br>
					<input class="login" type="password" name="pin" placeholder="Enter PIN"><br>
					<input class="b-login" type="submit" value="Login">
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
