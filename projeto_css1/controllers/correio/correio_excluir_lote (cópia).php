<?php
/**************************************************************
* Local/nome do script: correio/correio_excluir_lote.php
* Só executa se for chamado pelo formulario, senão chama o script de "acesso negado"
* Em caso de erros, cria variaveis de sessão com mensagens de alertas que serão utilizadas
* pelo script usuario/alertas.inc.php(incluido pelo index.php)
* Em caso de tudo certo, grava no BD
* Ao final de tudo, redireciona para a caixa de correio que chamou (entrada, já lidos, enviados)
* *************************************************************/
session_start();

$inc = "sim";

include_once('../../config.inc.php');

if (isset($_POST['flag'])){

	require_once(PATH . '/controllers/autenticacao/autentica.inc.php');
	$pagina = $_POST['pagina'];

	if($_POST['lote'] == 'sim'){

		if($_POST['input_sent'] == "i" or $_POST['input_sent'] == "l"){
			echo "ok";

			//deleta uma linha para cada id_correio na tabela de correio_recebidos
			$id_correio = $_POST['id_correio'];

				$con_del = $mysqli->query("DELETE FROM correio_recebidos WHERE id_correio = '$id_correio' AND destinatario = '$id_usuario'");

				$teste_del = $mysqli->query("SELECT id FROM correio_recebidos WHERE id_correio = '$id_correio' AND destinatario = '$id_usuario'");
			//}
		}
		else {
			//echo "erro";

			//foreach($_POST['lote'] as $id_correio){

				$con_del = $mysqli->query("UPDATE correio_enviados SET excluida = 'sim' WHERE id_correio = '$id_correio'");

				$teste_del = $mysqli->query("SELECT id_correio FROM correio_enviados WHERE id_correio = '$id_correio' AND excluida = 'nao'");
			//}
		}

		if($teste_del->num_rows == 0){

			$_SESSION['correio_excluir_sucesso'] = "Mensagem excluída com sucesso!";
			$_SESSION['botao'] = "success";
		}
		else{

			$_SESSION['correio_excluir_erro'] = "ERRO C-03: msg não excluída, tente novamente!<br />Em caso de persistir o erro, entrar em contato com o suporte técnico.";
			$_SESSION['botao'] = "danger";
		}

		$flag = md5("correio_excluir");
	}
	header(sprintf("Location:../../".$pagina."?flag=$flag"));
}
else {
	include_once(PATH . '/controllers/autenticacao/'.ACESSO_NEGADO);
}
?>
