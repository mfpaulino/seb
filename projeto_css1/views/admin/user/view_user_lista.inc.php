<?php
$sql = "SELECT id_usuario, cpf, rg, nome_guerra, nome, email, ritex, celular, avatar, dt_cad, usuarios.id_posto, p.posto, codom, usuarios.id_perfil, pe.perfil, ultimo_acesso, acesso_anterior, status from usuarios, postos p, adm_perfis pe where usuarios.id_posto = p.id_posto and usuarios.id_perfil = pe.id_perfil and cpf <> '$cpf' order by usuarios.id_posto";
$con_usuarios = $mysqli->query($sql);
?>
<div class="box box-solid bg-olive collapsed-box">
	<div class="box-header">
		<i class="fa fa-user"></i>
		<h3 class="box-title">Usuários (Cadastrados)</h3>
		<div class="pull-right box-tools">
			<div class="btn-group">
				<button type="button" title="Exibir Menu" class="btn bg-olive btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down fa-lg"></i></button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li><a href="#" data-toggle="modal" data-target="#modalExibirUser">Impressão</a></li>

				</ul>
			</div>
			<button type="button" title="Expandir/Encolher" class="btn bg-olive btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" title="Ocultar" class="btn bg-olive btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-footer text-black" style="border:1px solid black;">
		<div class="col-sm-12">
	<table class="table table-striped">
		<tr class="text-bold">
			<td>Usuário</td>
			<td>Perfil</td>
			<td>Status</td>
			<td class="text-center">Ação</td>
		</tr>
	<?php
	while ($rows =  $con_usuarios->fetch_assoc()){
		$user_codom =  $rows['codom'];

		$sql = "select sigla, denominacao from cciex_om where codom = $user_codom";
		$con_om = $mysqli1->query($sql);
		$row_om = $con_om->fetch_assoc();


		/*** verifica se o usuário possui alguma entrada na tabela de logs ****/
		$user_cpf = $rows['cpf'];
		$sql_user_log = "SELECT id_log FROM logs WHERE cpf = '$user_cpf'";
		$resultado = $mysqli->query($sql_user_log);


		if($resultado->num_rows > 0){
			$btn_status_e = "disabled";
		}
		else {
			$btn_status_e = "";
		}
		/****/
		?>
		<tr>
			<td><?php echo $rows['posto'] ." ". $rows['nome_guerra']; ?></td>
			<td><?php echo $rows['perfil']; ?></td>
			<td><?php echo $rows['status'];?></td>
			<td class="text-center">
				<!--botao Visualizar-->
				<button type="button" class="btn btn-xs btn-primary"
					data-tooltip="tooltip" title="Exibir Perfil"
					data-toggle="modal"
					data-target="#modalUserPerfil"
					data-id_usuario="<?php echo $rows['id_usuario'];?>"
					data-cpf="<?php echo $rows['cpf'];?>"
					data-rg="<?php echo $rows['rg'];?>"
					data-nome="<?php echo $rows['nome'];?>"
					data-email="<?php echo $rows['email'];?>"
					data-ritex="<?php echo $rows['ritex'];?>"
					data-celular="<?php echo $rows['celular'];?>"
					data-usuario="<?php echo $rows['posto'].' '.$rows['nome_guerra'];?>"
					data-id_perfil="<?php echo $rows['id_perfil'];?>"
					data-perfil="<?php echo $rows['perfil'];?>"
					data-unidade="<?php echo $row_om['sigla'];?>"
					data-avatar="<?php echo "views/avatar/".$rows['avatar'];?>"
					data-doc=<?php echo $row_om['sigla'];?>"
					>
					<i class="fa fa-search"></i>
				</button>
				<!--botao ResetarSenha-->
				<button form="formSenha" type="submit" class="btn btn-xs btn-primary"
					data-toggle="confirmation"
					data-placement="left"
					data-btn-ok-label="Continuar"
					data-btn-ok-icon="glyphicon glyphicon-share-alt"
					data-btn-ok-class="btn-success"
					data-btn-cancel-label="Parar"
					data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
					data-btn-cancel-class="btn-danger"
					data-title="Confirma redefinir senha do usuário?"
					data-content="Nova senha será igual ao CPF">
					<i class="fa fa-key" ></i>
				</button>
				<!--botao Excluir-->
				<button form="formExcluir" type="submit" <?php echo $btn_status_e;?> class="btn btn-xs btn-primary"
					data-toggle="confirmation"
					data-placement="left"
					data-btn-ok-label="Continuar"
					data-btn-ok-icon="glyphicon glyphicon-share-alt"
					data-btn-ok-class="btn-success"
					data-btn-cancel-label="Parar"
					data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
					data-btn-cancel-class="btn-danger"
					data-title="Confirma exclusão do usuário?"
					data-content="">
					<i class="fa fa-trash" ></i>
				</button>
				<form id="formSenha" action="controllers/admin/user/user_alterar.php" method="POST">
					<input type="hidden" name="id_usuario"  value = "<?php echo $rows['id_usuario']; ?>" />
					<input type="hidden" name="cpf"  value = "<?php echo $rows['cpf']; ?>" />
					<input type="hidden" name = "flag" value="resetar_senha"/>
				</form>
				<form id="formExcluir" action="controllers/admin/user/user_alterar.php" method = "POST">
					<input type="hidden" name="flag" value="excluir" />
					<input type="hidden" name="id_usuario" value="<?php echo $rows['id_usuario'];?>" />
				</form>
			</td>
		</tr>
	<?php } ?>
			</table>
		</div>
	</div>
</div>