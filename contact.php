<?php
	include "connection.php";
	session_start();
	$_SESSION['page'] = "contact.php";

	if(!isset($_SESSION['login']))
		header("Location: login.php");

	if(isset($_POST['mob'])) {
		if (strlen($_POST['mob']) > 0 && (!is_numeric($_POST['mob']) || strlen($_POST['mob']) != 10))
			$_SESSION['error'] = "Invalid Mobile No.";
		elseif (strlen($_POST['email']) > 0 && (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
			$_SESSION['error'] = "Invalid Email";
		else {

			if(strlen($_POST['mob']) > 0) {
				$stmt = $conn->prepare("UPDATE mvmt_records SET mobile= :mob WHERE accountNo = :ac_no");
				$stmt->execute(array(
					":mob" => $_POST['mob'],
					":ac_no" => $_SESSION['ac_no']
				));
			}
			if(strlen($_POST['email']) > 0) {
				$stmt = $conn->prepare("UPDATE mvmt_records SET email= :email WHERE accountNo = :ac_no");
				$stmt->execute(array(
					":email" => $_POST['email'],
					":ac_no" => $_SESSION['ac_no']
				));
			}

			$_SESSION['logout'] = "Contact updated successfully.";
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
		<link rel="stylesheet" href="styles/contact-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="contact">Update Contact</h2>

				<form method="POST">
					<label class="contact">Mobile No.</label><br>
					<input class="contact" type="text" name="mob" placeholder="Enter No."><br>
					<label class="contact">Email</label><br>
					<input class="contact" type="text" name="email" placeholder="Enter Email"><br>
					<input class="b-contact" type="submit" value="Update">
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
