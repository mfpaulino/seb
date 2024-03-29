<div class="modal fade" data-backdrop="static" id="modalCadastrarCategoria" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header fundo">
				<h4 class="modal-title">Cadastrar Categoria</h4>
			</div>
			<div class="modal-body">
				<form name="form_categoria_cadastrar" id="form_categoria_cadastrar" action="controllers/admin/categoria/categoria_cadastrar.php" method="POST">
					<div class="form-group">
						<label for="categoria" class="control-label">*Categoria:</label>
						<input class="form-control" type="text" name="categoria"  id="categoria"  autofocus  placeholder="" />
					</div>
					<div class="form-group">
						<label for="localidade" class="control-label">*Localidades:</label>
						<br />
						<?php include_once('controllers/admin/categoria/listar_guarnicoes_cad.inc.php');?>
					</div>
					<div class="form-group">
						<input  type="hidden" name="flag" />
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Enviar</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>