<?php
/***********************************************************************************************************
* local/script name: ./index_visite.php                                                                    *
* Primeira tela de usuario logado, mas nao liberado                                                        *
* Inclui o form de alterar senha                                                                           *
* Inclui o form de visualizar dados                                                                        *
* Inclui o form de alterar dados                                                                           *
* Inclui o form de alterar Unidade                                                                         *
* Exibe na tela alertas diversos vindos do script 'controllers/usuario/usuario_alertas_criar.inc.php'      *
* **********************************************************************************************************/

$inc 	= "sim";
$pagina = strtr(end(explode('/', $_SERVER['PHP_SELF'])),'?', true);

include_once('config.inc.php');
include_once(PATH . '/controllers/autenticacao/autentica_visite.inc.php');

switch ($status_usuario){
	case ('recebido'):
		$status = "Aguardando liberação de acesso";
		break;

	case('desabilitado'):
		$status = "Usuário está desabilitado";
		break;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo TITULO;?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="componentes/externos/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="componentes/externos/bower_components/bootstrap/dist/css/bootstrapValidator.min.css" />
	<link rel="stylesheet" href="componentes/externos/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="componentes/externos/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="componentes/externos/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="componentes/externos/dist/css/skins/skin-green.css">
	<link rel="stylesheet" href="componentes/internos/css/siaudi.css">
</head>
<body class="hold-transition skin-green sidebar-mini">
	<?php include_once(PATH . '/componentes/internos/php/cabecalho.inc.php');?>
	<div class="wrapper">
		<header class="main-header">
			<a href="index2.html" class="logo">
				<span class="logo-mini"><b><-></b></span>
				<span class="logo-lg barra-top"><b>SIAUDI</b>/EB</span>
			</a>
			<nav class="navbar navbar-static-top" role="navigation">
				<section class="content-header barra-top" >
					<h1>
						<strong>SISTEMA DE AUDITORIA INTERNA DO EXÉRCITO</strong>
						<small></small>
					</h1>
				</section>
			</nav>
		</header>
		<!--Início Menu Lateral-->
		<aside class="main-sidebar">
			<section class="sidebar form-login">
				<ul class="sidebar-menu" data-widget="tree">
					<li><a href="#"><i class="fa fa-file"></i> <span>Guia do Usuário</span></a></li>
					<li><a href="#" data-toggle="modal" data-target="#modalTrocarSenha"><i class="fa fa-lock"></i> <span>Alterar senha</span></a></li>
					<?php $flag = md5("logout");?>
					<li><a href="controllers/autenticacao/logout.php?flag=<?php echo $flag;?>"><i class="fa fa-sign-out"></i> <span>Sair do sistema</span></a></li>
				</ul>
			</section>
		</aside>
		<!-- Fim Menu Lateral-->
		<div class="content-wrapper">
			<section class="content container-fluid">
				<div class="page-header">
					<h3>Informações de usuário: <u> &nbsp;<?php echo $posto_usuario . " " . $nome_guerra_usuario . "</u>&nbsp;&nbsp;(".$sigla_usuario.")";?></h3>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php include_once('views/usuario/view_usuario_status.inc.php');?>
						<?php
						if (isset($_GET['flag']) and ($_GET['flag'] == md5("usuario_alterar") or $_GET['flag'] == md5("senha_alterar") or $_GET['flag'] == md5("unidade_alterar") or $_GET['flag'] == md5("logout") )){

							include_once('controllers/usuario/usuario_alertas_criar.inc.php');
						}
						else {
							 include_once('controllers/usuario/usuario_alertas_destruir.inc.php');
						}
						?>
						<!-- Inicio modalVisualizar-->
						<?php include_once('views/usuario/view_usuario_dados.inc.php');?>
						<!-- Inicio modalEditar -->
						<?php include_once('views/usuario/form_usuario_alterar.inc.php');?>
						<!-- Inicio modalTrocarUnidade -->
						<?php include_once('views/usuario/form_unidade_alterar.inc.php');?>
						<!-- inicio modalTrocarSenha -->
						<?php include_once('views/usuario/form_senha_alterar.inc.php'); ?>
						<!-- Inicio modalAlerta-->
						<?php include_once('views/usuario/view_usuario_alertas.inc.php');?>
						<?php if(isset($_SESSION['alterar_senha_logout'])){session_destroy();}//termina a sessao se alterar a senha?>

					</div>
				</div>
			</section>
		</div>
		<?php include_once('componentes/internos/php/rodape.inc.php');?>
	</div>
	<script src="componentes/externos/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="componentes/externos/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="componentes/externos/bower_components/bootstrap/dist/js/bootstrapValidator.min.js"></script>
	<script src="componentes/externos/bower_components/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
	<script src="componentes/externos/dist/js/adminlte.min.js"></script>
	<script src="controllers/usuario/usuario_alterar.js"></script>
	<script src="controllers/usuario/senha_alterar.js"></script>
	<script type="text/javascript">
		$('#modalEditar').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var cpf = button.data('cpf') // Extract info from data-* attributes
			var rg = button.data('rg')
			var nome_guerra = button.data('nome_guerra')
			var nome = button.data('nome')
			var id_posto = button.data('id_posto')
			var posto = button.data('posto')
			var email = button.data('email')
			var ritex = button.data('ritex')
			var celular = button.data('celular')
			var unidade = button.data('unidade')
			var modal = $(this)

			modal.find('.modal-title').text('Usuário: ' + posto + ' ' + nome_guerra + ' - ' + unidade )
			modal.find('#cpf').val(cpf)
			modal.find('#rg').val(rg)
			modal.find('#email').val(email)
			modal.find('#ritex').val(ritex)
			modal.find('#celular').val(celular)
			modal.find('#posto').val(id_posto)
			modal.find('#nome_guerra').val(nome_guerra)
			modal.find('#nome').val(nome)
		})
	</script>
	<script>
		//script para receber a selecao da unidade de controle interno e atualizar o 2º select
		$(document).ready(function(){
			$("select[name=unidade_ci]").change(function(){
				$("select[name=codom]").html('<option value="">Carregando...</option>');
				$.post("listas/select_unidade_usuario.inc.php", {unidade_ci:$(this).val()},function(valor){$("select[name=codom]").html(valor);})
			 })
		 })
	</script>
	<script type="text/javascript">
		$('#modalTrocarUnidade').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var unidade = button.data('unidade')
			var modal = $(this)
			modal.find('.modal-title').text('Unidade atual: ' + unidade )
			modal.find('#unidade').val(unidade)
		})
	</script>
	<script>
		$('[data-toggle="confirmation"]').confirmation({
			onConfirm: function() {
				$('#form_altera_unidade').bootstrapValidator({
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
					fields: {
						unidade_ci: {
							validators: {
								notEmpty: {
									message:'preenchimento obrigatório'
								}
							}
						},
						codom: {
							validators: {
								notEmpty: {
									message:'preenchimento obrigatório'
								}
							}
						}
					}
				})
			}
		});
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