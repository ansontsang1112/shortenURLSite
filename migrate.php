<!doctype html>
<?php
session_start();

?>
<html lang="en">
  <head>
  	<title>Shorten URL (Migration)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="assets/css/style_migrate.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">RCN Member Linkage (Shorten URL)</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      	<div class="icon d-flex align-items-center justify-content-center">
		      		<span class="fa fa-user-o"></span>
		      	</div>
		      	<h3 class="text-center mb-4">RCN 會員連結系統</h3>
                        <?php if(isset($_GET['login'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            帳號或密碼錯誤
                        </div>
                        <?php } ?>
                        <div class="card" style="width: 100%;">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">DiscordID: <?php echo $_SESSION['userObject']->id; ?></li>
                                <li class="list-group-item">Username: <?php echo $_SESSION['userObject']->username; ?></li>
                                <li class="list-group-item">UID: <?php echo $_SESSION['uid']; ?></li>
                            </ul>
                        </div>
                        <hr>
                        <h3 class="text-center"><strong>請使用會員帳號進行連結</strong></h3>
						<form action="backend/pdo/migrate.php?migrate" method="post" class="login-form">
		      		<div class="form-group">
		      			<input type="text" class="form-control rounded-left" name="username" placeholder="帳號" required>
		      		</div>
	            <div class="form-group d-flex">
	              <input type="password" class="form-control rounded-left" name="password" placeholder="密碼" required>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="btn btn-primary rounded submit p-3 px-5">Link 連結</button>
	            </div>
	          </form>
	        </div>
				</div>
			</div>
		</div>
	</section>

	<script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
	</body>
</html>

