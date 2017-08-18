<?php
session_start();
$inc ="sim";

include('config.inc.php');
include_once(PATH . '/controllers/autenticacao/perfil.inc.php');

$agora = date("Y-m-d H:i:s");
$ultimoAcesso = $_SESSION['ultimoAcesso'];
$tempo_inatividade = (strtotime($agora)-strtotime($ultimoAcesso));

if($tempo_inatividade >= TEMPO_MAX_INATIVIDADE){ // TEMPO_SESSAO vem de constantes.inc.php
	session_destroy();
}

if(isset($_SESSION['cpf'])){?>
		<p><?php echo $posto_usuario . " " . $nome_guerra_usuario;?></p>
		<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	<?php
}
else { ?>
	<div class="pull-left info">
		<p></p>
		<a href="#"><i class="fa fa-circle text-danger"></i> Offline</a>
	</div>
	<?php
}
?>