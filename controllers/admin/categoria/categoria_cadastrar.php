<?php
/**************************************************************
* Local/nome do script: admin/categoria_cadastrar.php
* Só executa se for chamado pelo formulario, senão chama o script de "acesso negado"
* primeiramente destroi as variaveis de sessao de alertas de usuario
* Recebe todos os dados do formulario de cadastro de usuario
* Trata os valores recebidos com o método mysqli: mysqli_real_escape_string
* Usa a classe validaForm para fazer a validação dos dados
* Consulta o BD em busca da categoria para evitar duplicidade
* Em caso de erros, cria variaveis de sessão com mensagens de alertas que serão utilizadas
* pelo script admin/alertas.inc.php(incluido pelo admin.php)
* Em caso de tudo certo, grava no BD
* Ao final de tudo, redireciona para o admin.php
* *************************************************************/

session_start();

$inc = "sim";
include_once('../../../config.inc.php');

if (isset($_POST['flag']) and isset($_SESSION['cpf'])){

	require_once(PATH . '/controllers/autenticacao/autentica.inc.php');

	require_once(PATH . '/componentes/internos/php/validaForm.class.php');
	require_once(PATH . '/componentes/internos/php/funcoes.inc.php');

	$categoria	 = isset($_POST['categoria']) ? mysqli_real_escape_string($mysqli, $_POST['categoria']) : "";
	$localidade = isset($_POST['localidade']) ? $_POST['localidade'] : "";

	$validar = new validaForm();

	$validar->set('Categoria', 	 $categoria)->is_required()
			->set('Localidades', $localidade)->is_required();

	if ($validar->validate()){

		$busca_categoria = $mysqli->query("SELECT id_categoria FROM adm_categorias WHERE categoria = '$categoria'");
		$busca_localidade = $mysqli->query("SELECT id_categoria FROM adm_categorias WHERE localidades = '$localidade'");


		if($busca_categoria->num_rows > 0){

			$_SESSION['categoria_duplicada'] = "ERRO 039: Categoria já cadastrada!";
			$_SESSION['botao'] = "danger";

			$validacao = false;
		}
		if($busca_localidade->num_rows > 0){

			$_SESSION['localidade_duplicada'] = "ERRO 040: Localidade(s) já cadastrada(s)!";
			$_SESSION['botao'] = "danger";

			$validacao = false;
		}
		if($validacao !== false){

			/*****/
			$qtde = count($localidade);
			for($i = 0; $i < $qtde; $i++){
				$localidades = $localidades.$localidade[$i].",";//concatena as localidades com ",".
			}

			if($localidade <> ""){
				$localidades = substr($localidades, 0, -1);//elimina a ultima ",".
				$localidades = explode(",",$localidades);//cria um array separando pelas ",".
				$localidades = serialize($localidades);//cria uma string com o array serializado
			}
			/******/

			$resultado = $mysqli->query("INSERT INTO adm_categorias (categoria, localidades) VALUES ('$categoria', '$localidades')");

			if($resultado){

				/** log **/
				$log = "Cadastrou a categoria <u>" . $categoria . "</u>(".$localidade.").";
				$con_log = $mysqli->query("INSERT INTO logs SET cpf = '$cpf', codom = '$codom_usuario', acao = '$log', tabela = 'adm_categorias'");
				/** fim log **/

				$_SESSION['sucesso_cadastro_categoria'] = "Cadastro realizado com sucesso!";
				$_SESSION['botao'] = "success";
			}
			else{

				$_SESSION['erro_cadastro_categoria'] = "ERRO 041: cadastro não realizado, tente novamente!<br />Em caso de persistir o erro, entrar em contato com o suporte técnico.";
				$_SESSION['botao'] = "danger";
			}

		}
	}
	else {
		$_SESSION['erro_validacao_cadastrar_categoria'] = "ERRO 042: dados inconsistentes, preencha novamente o formulário!";
		$_SESSION['botao'] = "danger";

		$_SESSION['lista_erro_validacao_cadastrar_categoria'] = $validar->get_errors(); //Captura os erros de todos os campos
	}
	$flag = md5("categoria_cadastrar");
	header(sprintf("Location:../../../admin.php?flag=$flag"));
}
else {
	include_once(PATH . '/controllers/autenticacao/'.ACESSO_NEGADO);
}
?>
