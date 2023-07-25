<?php
// Start session
session_start();

// Get data from session
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $status = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

$postData = array();
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['postData']);
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Registration and Login</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" 	type="text/css" media="all">

<!-- Stylesheet file -->
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
</head>
<body>
<div class="container">
    
	<div class="wrapper">
		<h2>Create a New Account</h2>
		<!-- Status message -->
        <?php if(!empty($statusMsg)){ ?>
            <div class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></div>
        <?php } ?>
		<div class="regisFrm">
			<form action="userAccount.php" method="post">
				<input type="text" name="first_name" placeholder="FIRST NAME" value="<?php echo !empty($postData['first_name'])?$postData['first_name']:''; ?>" required="">
				<input type="text" name="last_name" placeholder="LAST NAME" value="<?php echo !empty($postData['last_name'])?$postData['last_name']:''; ?>" required="">
				<input type="email" name="email" placeholder="EMAIL" value="<?php echo !empty($postData['email'])?$postData['email']:''; ?>" required="">
				<input type="text" name="phone" placeholder="PHONE NUMBER" value="<?php echo !empty($postData['phone'])?$postData['phone']:''; ?>" required="">
				<input type="password" name="password" placeholder="PASSWORD" required="">
				<input type="password" name="confirm_password" placeholder="CONFIRM PASSWORD" required="">
				<div class="send-button">
					<input type="submit" name="signupSubmit" value="CREATE ACCOUNT">
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>