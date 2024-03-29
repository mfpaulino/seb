<?php
/**************************************************************
* Local/nome do script: usuario/cadastra.php
* Só executa se for chamado pelo formulario, senão chama o script de "acesso negado"
* primeiramente destroi as variaveis de sessao de alertas de usuario
* Recebe todos os dados do formulario de cadastro de usuario
* Trata os valores recebidos com o método mysqli: mysqli_real_escape_string
* Usa a classe validaForm para fazer a validação dos dados
* Consulta o BD em busca de CPF e email para evitar duplicidade
* Em caso de erros, cria variaveis de sessão com mensagens de alertas que serão utilizadas
* pelo script usuario/alertas.inc.php(incluido pelo index.php)
* Em caso de tudo certo, grava no BD
* Ao final de tudo, redireciona para o index.php
* *************************************************************/
session_start();

$inc = "sim";
include_once('../../config.inc.php');

if (isset($_POST['flag'])){

	require_once(PATH . '/componentes/internos/php/bcript.inc.php');
	require_once(PATH . '/componentes/internos/php/validaForm.class.php');
	require_once(PATH . '/componentes/internos/php/funcoes.inc.php');

	$cpf 		 = isset($_POST['cpf']) ? mysqli_real_escape_string($mysqli, $_POST['cpf']) : "";
	$senha 		 = isset($_POST['senha'])  ? mysqli_real_escape_string($mysqli, $_POST['senha']) : "";
	$senha1 	 = isset($_POST['senha1']) ? mysqli_real_escape_string($mysqli, $_POST['senha1']) : "";
	$rg 		 = isset($_POST['rg']) ? mysqli_real_escape_string($mysqli, $_POST['rg']) : "";
	$nome_guerra = isset($_POST['nome_guerra']) ? mysqli_real_escape_string($mysqli, formata_nome($_POST['nome_guerra'])) : "";
	$posto 		 = isset($_POST['posto']) ? mysqli_real_escape_string($mysqli, $_POST['posto']) : "";
	$nome 		 = isset($_POST['nome']) ? mysqli_real_escape_string($mysqli, formata_nome($_POST['nome'])) : "";
	$email 		 = isset($_POST['email']) ? mysqli_real_escape_string($mysqli, $_POST['email']) : "";
	$codom 		 = isset($_POST['codom']) ? mysqli_real_escape_string($mysqli, $_POST['codom']) : "";
	$perfil		 = isset($_POST['perfil']) ? mysqli_real_escape_string($mysqli, $_POST['perfil']) : "";
	$ritex		 = isset($_POST['ritex']) ? mysqli_real_escape_string($mysqli, $_POST['ritex']) : "";
	$fixo		 = isset($_POST['fixo']) ? mysqli_real_escape_string($mysqli, $_POST['fixo']) : "";
	$celular	 = isset($_POST['celular']) ? mysqli_real_escape_string($mysqli, $_POST['celular']) : "";

	$validar = new validaForm();

	$validar->set('CPF', 			$cpf)->is_cpf()
			->set('Senha', 			$senha)->is_not_equals($cpf, true,"CPF")->min_length(8)->is_equals($senha1, true, "Confirmação da senha")
			->set('RG', 			$rg)->is_required()->is_num()
			->set('Posto', 			$posto)->is_required()
			->set('Nome', 			$nome)->is_required()
			->set('Nome de guerra', $nome_guerra)->is_required()
			->set('E-mail',			$email)->is_email()
			//->set('RITEx',		$ritex)->is_required()->is_num()->min_length(7)->max_length(7)
			//->set('Tel Fixo',		$ritex)->is_required()->is_num()->min_length(10)->max_length(10)
			//->set('Celular',		$celular)->is_required()->is_num()->min_length(10)->max_length(11)
			->set('Unidade', 		$codom)->is_required()
			->set('Perfil', 		$perfil)->is_required();


	if ($validar->validate()){

		$busca_cpf = $mysqli->query("SELECT id_usuario FROM usuarios WHERE cpf = '$cpf'");

		if($busca_cpf->num_rows == 1){

			$_SESSION['duplo_cpf'] = "ERRO 021: CPF já existe!";
			$_SESSION['botao'] = "danger";

			$validacao = false;
		}

		$busca_email = $mysqli->query("SELECT id_usuario FROM usuarios WHERE email = '$email'");

		if($busca_email->num_rows == 1){

			$_SESSION['duplo_email'] = "ERRO 022: e-mail já foi cadastrado para outro usuário!";
			$_SESSION['botao'] = "danger";

			$validacao = false;
		}

		if($validacao !== false){

			if ($_FILES['avatar']['name'] <> ''){

			  $ext = strtolower(substr($_FILES['avatar']['name'],-4)); //Pegando extensão do arquivo
			  $avatar = $cpf . $ext; //Definindo um novo nome para o arquivo
			  $dir = PATH . '/views/avatar/'; //Diretório para uploads

			  move_uploaded_file($_FILES['avatar']['tmp_name'], $dir.$avatar); //Fazer upload do arquivo
		    }
		    else {
				$avatar = "default_avatar.jpg";
			}

			$senha_criptografada = Bcrypt::hash($senha);

			$dt_cad = date('Y-m-d');

			/********* perfil da OM do usuario (CCIEx, ICFEx, Unidade) ****/

			$sql = "select sigla from cciex_om where codom = '$codom'";
			$con_om = $mysqli1->query($sql);
			$row = $con_om->fetch_assoc();

			if(strpos($row['sigla'],'CCIEx') !== FALSE){
				$perfil_om = 1;
			}
			else if(strpos($row['sigla'],'ICFEx') !== FALSE){
				$perfil_om = 2;
			}
			else {
				$perfil_om = 3;
			}
			/**********************************/

			$resultado = $mysqli->query("INSERT INTO usuarios (cpf, senha, rg, nome_guerra, nome, email, ritex, fixo, celular, avatar, dt_cad, id_posto, codom, id_perfil, id_perfil_om, status) VALUES ('$cpf', '$senha_criptografada', '$rg', '$nome_guerra', '$nome', '$email', '$ritex', '$fixo', '$celular', '$avatar', '$dt_cad','$posto', '$codom', '$perfil', '$perfil_om', 'Recebido')");

			if($resultado){

				$_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso!<br />Faça o login com o CPF e senha cadastrados.";
				$_SESSION['botao'] = "success";
			}
			else{

				$_SESSION['erro_cadastro'] = "ERRO 023: cadastro não realizado, tente novamente!<br />Em caso de persistir o erro, entrar em contato com o suporte técnico.";
				$_SESSION['botao'] = "danger";
			}

		}
	}
	else {
		$_SESSION['erro_validacao_cadastrar'] = "ERRO 024: dados inconsistentes, preencha novamente o formulário!";
		$_SESSION['botao'] = "danger";

		$_SESSION['lista_erro_validacao_cadastrar'] = $validar->get_errors(); //Captura os erros de todos os campos
	}
	$flag = md5("usuario_cadastrar");
	header(sprintf("Location:../../index.php?flag=$flag"));
}
else {
	include_once(PATH . '/controllers/autenticacao/'.ACESSO_NEGADO);
}
?>
