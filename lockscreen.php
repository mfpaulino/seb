<?php
/***********************************************************************************************************
* local/script name: ./lockscreen.php                                                                      *
* Tela de bloqueio                                                                                         *
* Interrompe o tempo de sessao                                                                             *
* **********************************************************************************************************/
$inc = "sim";
include_once('config.inc.php');

session_start();

if(!isset($_SESSION['cpf'])){//se nao estiver logado volta para tela de login
	header("Location:index.php");
}
else{
	//recebe apenas o valor do flag com a pagina original
	$pagina_lock = str_replace('lockscreen.php?flag=','',strtr(end(explode('/', $_SERVER['REQUEST_URI'])),'', true));

	$_SESSION['acesso'] = "lock";//cria a variavel de sessao que será utilizada no script login.php

	include_once(PATH . '/controllers/autenticacao/perfil.inc.php');

	if(isset($_SESSION['senha_errada'])){

		$msg = 'sim';

		unset($_SESSION['senha_errada']);

		$botao = "danger";
		$msg0 = "ERRO A-03: senha incorreta!";

		include_once('views/usuario/view_usuario_alertas.inc.php');
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SIAUD-BLOQUEIO</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="componentes/externos/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="componentes/externos/bootstrap/plugins/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="componentes/externos/bootstrap/plugins/Ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="componentes/externos/template/css/AdminLTE.css">
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
			<div class="lockscreen-logo">
				<a href="index.php"><b>SIAUDI</b>-EB</a>
			</div>
			<div class="lockscreen-name"><?php echo $posto_usuario . " " . $nome_guerra_usuario;?></div>
			<p>&nbsp;</p>
			<div class="lockscreen-item">
				<div class="lockscreen-image">
					<img src="views/avatar/<?php echo $avatar_usuario;?>" alt="User Image">
				</div>
				<form class="lockscreen-credentials" method="POST" action="controllers/autenticacao/login.php">
					<div class="input-group">
						<input type="password" name="senha" class="form-control" placeholder="Senha" required >
						<input type="hidden" name="cpf" value="<?php echo $cpf;?>" />
						<input type="hidden" name="flag" value="<?php echo $pagina_lock;?>"/>
						<input type="hidden" name="flag1" value="lock" />
						<div class="input-group-btn">
							<button type="submit" class="btn"><i class="fa fa-unlock text-muted"></i></button>
						</div>
					</div>
				</form>
			</div>
			<div class="help-block text-center">
				Entre com a senha para desbloquear a tela
			</div>
			<div class="text-center">
				<?php $flag = md5("logout");?>
				<a href="controllers/autenticacao/logout.php?flag=<?php echo $flag;?>">Fazer login com outro usuário</a>
			</div>
			<div class="lockscreen-footer text-center">
				Copyright &copy; 2018 <b><a href="https://adminlte.io" class="text-black">CCIEx</a></b>
				<br>
				Todos os direitos reservados
			</div>
		</div>
		<script src="componentes/externos/jquery/dist/jquery.min.js"></script>
		<script src="componentes/externos/bootstrap/dist/js/bootstrap.min.js"></script>
		<script>
		</script>
		<?php
		if ($msg <> ""){?>
			<script>
				$(document).ready(function(){
					$('#modalAlerta').modal('show');
				});
			</script>
		<?php
		}
		?>
	</body>
	</html>
<?php
}
?>