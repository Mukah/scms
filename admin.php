<?php session_start() ?>
<?php require_once("config.php") ?>
<!DOCTYPE html>
<html>
<head>
	<title>sCMS</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">	
	<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div style="width: 300px; height: 200px; position: absolute; top: 50%; left: 50%; margin-top: -100px; margin-left: -150px;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Autenticação</h3>
			</div>
			<div class="panel-body">
				<form name="login" method="post" action="">
					<p>
						<input type="text" name="email" placeholder="E-mail" class="form-control">
					</p>
					<p>
						<input type="password" name="password" placeholder="Senha" class="form-control">
					</p>
					<p>
						<input type="submit" value="Entrar" class="btn btn-default btn-primary">
					</p>
				</form>
			</div>
		</div>
		<?php
			if($_POST) {
				$p_email = $_POST["email"];
				$p_password = $_POST["password"];

				if(	isset($p_email) and !empty($p_email) and
					isset($p_password) and !empty($p_password)) {

					$user = mysql_query("SELECT * FROM users WHERE email = '$p_email' AND password = '$p_password';");
					if(mysql_num_rows($user) == 1) {
						$_SESSION['uid'] = mysql_result($user, 0, "user_id");
						header('location: home.php');
					} else {
						?>
							<div class="alert alert-danger">Usuário ou senha inválidos.</div>
						<?php
					}
				} else {
					?>
						<div class="alert alert-warning">Preencha todos os campos.</div>
					<?php
				}
			}
		?>
	</div>
</body>
</html>