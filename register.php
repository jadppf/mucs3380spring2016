<?php
    if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
    	$url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	header('Location: ' . $url);
    }
    session_start();


    if(isset($_SESSION["username"]) && isset($_SESSION["user_type"])) {
    	if($_SESSION["user_type"] != 1) {
    		header("Location: index.php");
    	}
    } else {
    	header("Location: index.php");
    }
    ?>
    <html>
    <head>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>
    	<div class="container">
    		<div class="row">
    			<div class="col-md-4 col-sm-4 col-xs-3"></div>
    			<div class="col-md-4 col-sm-4 col-xs-6">
    				<h2>Create a User</h2>
    				<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    					<div class="row form-group">
    						<input class='form-control' type="text" name="username" placeholder="username">
    					</div>
    					<div class="row form-group">
    						<input class='form-control' type="password" name="password" placeholder="password">
    					</div>
    					<div class="row form-group">
    						<label class='inputdefault'>User Type</label>
    						<div class="radio">
    							<label><input type="radio" name="user_type" value = "1">Administrator</label>
    						</div> 
    						<div class="radio">
    							<label><input type="radio" name="user_type" value = "NULL">Regular User</label>
    						</div>
    					</div>
    					<div class="row form-group">
    						<input class=" btn btn-info" type="submit" name="submit" value="Register"/>
    						<a href="index.php" class="btn btn-primary text">Back to Summary Page</a>
    					</div>
    				</form>
    			</div>
    		</div>
    		<?php
				if(isset($_POST['submit'])) { // Was the form submitted?
					if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['user_type'])) {
						echo "<div class='alert alert-warning'>Please fill out the form completely</div>";
						exit;
					}
					$link = mysqli_connect("localhost", "root", "lyss11246!", "lab8db")  or die ("Connection Error " . mysqli_error($link)); //Can't give away my password
					$sql = "INSERT INTO user(username,salt,hashed_password,admin) VALUES (?,?,?,?)";
					if ($stmt = mysqli_prepare($link, $sql)) {
						$user = $_POST['username'];
						$user_type = $_POST['user_type'];
						$salt = mt_rand();
						$hpass = password_hash($salt.$_POST['password'], PASSWORD_BCRYPT)  or die("bind param");
						mysqli_stmt_bind_param($stmt, "ssss", $user, $salt, $hpass, $user_type) or die("bind param");
						if(mysqli_stmt_execute($stmt)) {
							echo "<div class='alert alert-Success'>You have successfully registered.</div>";
							header( "Refresh:2; url=index.php", true, 303);
						} else {
							echo "<div class='alert alert-danger'>Registration failed.  That username may already be in use. Please try again.</div>";
						}
						$result = mysqli_stmt_get_result($stmt);
					} else {
						die("prepare failed");
					}
				}
				?>
			</div>
		</body>
		</html>



<!--The MIT License (MIT)
Copyright (c) 2016 Hunter Ginther, Jakob Daugherty, Zach Dolan, Kevin Free, Michael McLaughlin, and Alyssa Nielsen 

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->