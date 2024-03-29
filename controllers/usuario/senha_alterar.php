<?php
/***********************************************************************************************************
* local/script name: ./controllers/usuario/senha_alterar.php                                               *
* Usa script de criptografia e classe de validacao                                                         *
* Mínimo de 8 caracteres e diferente do CPF                                                                 *
* Cria msg que serao utilizadas pelo script ./controllers/usuario/usuario_alertas_criar.inc.php            *
* **********************************************************************************************************/
session_start();

$inc = "sim";
include_once('../../config.inc.php');

if(isset($_POST['flag']) and isset($_SESSION['cpf'])){

	include_once(PATH . '/componentes/internos/php/bcript.inc.php');//arquivo para criptografia
	include_once(PATH . '/componentes/internos/php/validaForm.class.php'); //classe para validacao de dados
	include_once(PATH . '/controllers/autenticacao/perfil.inc.php');

	$pagina 	 = $_POST['flag1'];

	if ($pagina == PAGINA_VISITANTE ){

		require_once(PATH . '/controllers/autenticacao/autentica_visite.inc.php');
	}
	else{

		require_once(PATH . '/controllers/autenticacao/autentica.inc.php');
	}

	$senha 		 = isset($_POST['senha_nova'])  ? mysqli_real_escape_string($mysqli, $_POST['senha_nova']) : "";
	$senha1 	 = isset($_POST['senha_nova1']) ? mysqli_real_escape_string($mysqli, $_POST['senha_nova1']) : "";

	$validar = new validaForm(); //cria objeto da classe de validacao

	$validar->set('Senha', $senha)->is_not_equals($cpf, true,"CPF")->min_length(8)->is_equals($senha1, true, "Confirmação da senha");

	if ($validar->validate()){

		$senha_cript = Bcrypt::hash($senha1);//criptografa a senha digitada

		//salva a nova senha no BD
		$con_update = $mysqli->prepare("UPDATE usuarios SET senha = ? WHERE cpf ='$cpf'");
		$con_update->bind_param('s', $senha_cript);
		$con_update->execute();
		$mysqli->close();

		if($con_update->affected_rows <> 0 ){
			$_SESSION['alterar_senha_logout'] = "";
			$_SESSION['alterar_senha_sucesso'] = "Senha alterada com sucesso!<br />Faça login com a nova senha.";
			$_SESSION['botao'] = "success";
		}
		else{
			$_SESSION['alterar_senha_erro_bd'] = "ERRO 015: senha não alterada, tente novamente!";
			$_SESSION['botao'] = "danger";
		}

	}
	else{
		$_SESSION['alterar_senha_erro_validacao'] = "ERRO 016: dados inconsistentes, preencha novamente o formulário!";
		$_SESSION['alterar_senha_erro_validacao_lista'] = $validar->get_errors(); //Captura os erros de todos os campos
		$_SESSION['botao'] = "danger";
	}

	$flag = md5("senha_alterar");
	header(sprintf("Location:../../".$pagina."?flag=$flag"));//redireciona para a pagina que chamou o script
}
else {
	include_once('../autenticacao/'.ACESSO_NEGADO);
}
?>
