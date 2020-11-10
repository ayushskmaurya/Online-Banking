<?php
	include "connection.php";
	session_start();

	if(isset($_SESSION['login']))
		header("Location:".$_SESSION['page']);

	$ac_no = rand(111111111111, 999999999999);
	$stmt = $conn->query("SELECT accountNo FROM mvmt_records WHERE accountNo = ".$ac_no);
		while($stmt->rowCount() != 0) {
			$ac_no = rand(111111111111, 999999999999);
			$stmt = $conn->query("SELECT accountNo FROM mvmt_records WHERE accountNo = ".$ac_no);
		}

	if(isset($_POST['fname'])) {

		if(strlen($_POST['fname']) == 0 || strlen($_POST['lname']) == 0
	  || strlen($_POST['pin']) == 0 || strlen($_POST['bal']) == 0
		|| strlen($_POST['mob']) == 0)
			$_SESSION['error'] = "Please fill the required fields";

		elseif (!is_numeric($_POST['pin']) || strlen($_POST['pin']) != 4)
			$_SESSION['error'] = "Invalid PIN";
		elseif ($_POST['pin'] !== $_POST['rpin'])
			$_SESSION['error'] = "PINs entered are not same";

		elseif (!is_numeric($_POST['bal']))
			$_SESSION['error'] = "Invalid Opening Balance";

		elseif (strlen($_POST['aadhaar']) > 0 && (!is_numeric($_POST['aadhaar']) || strlen($_POST['aadhaar']) != 12))
			$_SESSION['error'] = "Invalid Aadhaar No.";

		elseif (!is_numeric($_POST['mob']) || strlen($_POST['mob']) != 10)
			$_SESSION['error'] = "Invalid Mobile No.";

		elseif (strlen($_POST['email']) > 0 && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			$_SESSION['error'] = "Invalid Email";

		else {
			$stmt = $conn->prepare("INSERT INTO mvmt_records (accountNo, firstName, lastName, pin, balancedAmount, aadhaar, mobile, email) VALUES (:ac_no, :fname, :lname, :pin, :bal, :aadhaar, :mob, :email)");
			$stmt->execute(array(
				':ac_no' => $ac_no,
				':fname' => $_POST['fname'],
				':lname' => $_POST['lname'],
				':pin' => $_POST['pin'],
				':bal' => $_POST['bal'],
				':aadhaar' => $_POST['aadhaar'],
				':mob' => $_POST['mob'],
				':email' => $_POST['email']
			));

			$stmt = $conn->prepare("INSERT INTO mvmt_statements (accountNo, info, dateTime, amount) VALUES  (:ac_no, :detail, now(), :bal)");
			$stmt->execute(array(
				':ac_no' => $ac_no,
				':detail' => "Opening Balance",
				':bal' => $_POST['bal']
			));

			$_SESSION['logout'] = "Account created successfully! Please Login to continue.<br>Account No.: ".$ac_no;
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
		<link rel="stylesheet" href="styles/create-styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			<h2 class="create">Create Account</h2>

				<form method="POST">
					<label class="create">First Name <span id="a">*</span></label><br>
					<input class="create" type="text" name="fname" placeholder="Enter First Name"><br>
					<label class="create">Last Name <span id="a">*</span></label><br>
					<input class="create" type="text" name="lname" placeholder="Enter Last Name"><br>
					<label class="create">PIN <span id="a">*</span></label><br>
					<input class="create" type="password" name="pin" placeholder="Enter PIN"><br>
					<label class="create">Re-enter PIN <span id="a">*</span></label><br>
					<input class="create" type="password" name="rpin" placeholder="Enter PIN"><br>
					<label class="create">Opening Balance <span id="a">*</span></label><br>
					<input class="create" type="text" name="bal" placeholder="Enter Amount"><br>
					<label class="create">Aadhaar Card No.</label><br>
					<input class="create" type="text" name="aadhaar" placeholder="Enter Aadhaar No"><br>
					<label class="create">Mobile No. <span id="a">*</span></label><br>
					<input class="create" type="text" name="mob" placeholder="Enter Mobile No"><br>
					<label class="create">Email</label><br>
					<input class="create" type="text" name="email" placeholder="Enter Email"><br>
					<input class="b-create" type="submit" value="Create Account">
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
