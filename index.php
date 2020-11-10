<?php
	session_start();
	$_SESSION['page'] = "index.php";
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>MVMT Bank</title>
		<link rel="icon" href="files/Logo.jpg">
		<link rel="stylesheet" href="styles/styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
	</head>
	<body>
		<?php include "header.php"; ?>
		<div class="outerDiv">
			
			<?php
				if(isset($_SESSION['logout'])) {
					echo "<p class='logout'>".$_SESSION['logout']."</p>";
					unset($_SESSION['logout']);
				}
			?>

			<div class="row">
				<div class="item">
					<a class="item" href="create.php">Create Account</a>
					<p class="info">Create digital zero balance Bank Account online.</p>
				</div>
				<div class="item">
					<a class="item" href="deposit.php">Deposit Money</a>
					<p class="info">Deposit Money on your account within few seconds.</p>
				</div>
				<div class="item">
					<a class="item" href="withdraw.php">Withdraw Money</a>
					<p class="info">Withdraw Money from your account within few seconds.</p>
				</div>
				<div class="item">
					<a class="item" href="balance.php">Request Balance</a>
					<p class="info">Check Balanced amount in your account online.</p>
				</div>
			</div>

			<div class="row">
				<div class="item">
					<a class="item" href="btransfer.php">Bank Transfer</a>
					<p class="info">Money Transfer to other Bank Account online.</p>
				</div>
				<div class="item">
					<a class="item" href="ptransfer.php">Phone Number</a>
					<p class="info">Money Transfer to other Account with associated phone number.</p>
				</div>
				<div class="item">
					<a class="item" href="info.php">User Information</a>
					<p class="info">Check your bank account details.</p>
				</div>
				<div class="item">
					<a class="item" href="statement.php">Account Statement</a>
					<p class="info">Check the Account Statement online.</p>
				</div>
			</div>

			<div class="row">
				<div class="item">
					<a class="item" href="aadhaar.php">Link Aadhaar Card</a>
					<p class="info">Link your Aadhaar Card with Account online.</p>
				</div>
				<div class="item">
					<a class="item" href="contact.php">Update Contact</a>
					<p class="info">Update your contact information online.</p>
				</div>
				<div class="item">
					<a class="item" href="pin.php">Change PIN</a>
					<p class="info">Change PIN within seconds.</p>
				</div>
				<div class="item">
					<a class="item" href="close.php">Close Account</a>
					<p class="info">Close your Savings Account.</p>
				</div>
			</div>
		
		</div>
		<?php include "footer.html"; ?>
	</body>
</html>
