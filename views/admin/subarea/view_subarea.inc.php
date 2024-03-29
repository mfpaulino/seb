<div class="box box-solid bg-yellow collapsed-box">
	<div class="box-header">
		<i class="fa fa-bars"></i>
		<h3 class="box-title">Subáreas/Subprocessos</h3>
		<div class="pull-right box-tools">
			<div class="btn-group">
				<button type="button" title="Exibir Menu" class="btn bg-yellow-gradient btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down fa-lg"></i></button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li><a href="#" data-toggle="modal" data-target="#modalCadastrarSubarea">Cadastrar Subárea/Subprocesso</a></li>
					<li class="divider"></li>
					<li><a href="#" data-toggle="modal" data-target="#modalListarSubarea">Exibir Lista</a></li>
				</ul>
			</div>
			<button type="button" title="Expandir/Encolher" class="btn bg-yellow-gradient btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" title="Ocultar" class="btn bg-yellow-gradient btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body no-padding" style="display:none;">
	</div>
	<div class="box-footer text-black" style="border:1px solid black;">
		<div class="row">
			<div class="col-sm-12">
				<form class="form-horizontal" method="POST" action="controllers/admin/subarea/subarea_alterar.php">
					<div class="box-body">
						<?php include_once('listas/admin/select_subarea_adm.inc.php');?>
					</div>
					<div class="box-footer pull-right">
						<!--botao Alterar subarea-->
						<button id="btnAlteraSubarea" type="button" class="btn btn-xs btn-warning"
							data-tooltip="tooltip" title=""
							data-toggle="modal"
							data-target="#modalAlterarSubarea">
							<i class="fa fa-pencil"></i> Alterar
						</button>
						<!--botao Excluir subarea-->
						<button id="btnExcluiSubarea" type="submit" class="btn btn-xs btn-danger" data-toggle="confirmation"
							data-placement="left"
							data-btn-ok-label="Continuar"
							data-btn-ok-icon="glyphicon glyphicon-share-alt"
							data-btn-ok-class="btn-success"
							data-btn-cancel-label="Parar"
							data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
							data-btn-cancel-class="btn-danger"
							data-title="Confirma exclusão da Subárea?"
							data-content="">
							<i class="fa fa-trash"></i> Excluir
						</button>
						<!--botao Vincular-->
						<button id="btnSubareaVincula" type="button" class="btn btn-xs btn-primary"
							data-tooltip="tooltip" title="Vincular"
							data-toggle="modal"
							data-target="#modalSubareaVincular">
							<i class="fa fa-share-alt"></i> Vincular
						</button>
					</div>
					<input type="hidden" name="flag" value="excluir" />
				</form>
			</div>
		</div>
	</div>
</div>