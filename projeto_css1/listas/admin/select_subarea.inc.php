<?php
//select_subarea.inc.php
$inc = 'sim';
include_once('../../config.inc.php');

if(isset($_POST['area'])){

	$area = explode("|", $_POST['area']);//0=id_area 1=area (vem do select_area.inc.php)

	$sql = 	"SELECT id_subarea, subarea FROM adm_subareas WHERE id_area = '$area[0]' ORDER BY subarea";
	$con_subarea = $mysqli->query($sql);
	$mysqli->close();

	$num_rows_subarea = $con_subarea->num_rows;

	if($num_rows_subarea == 0){
	   echo  '<option value="">Aguardando a seleção da Área...</option>';
	}
	else {
		echo '<option value="">Selecione a Subárea...</option>';
		while($rows_subarea = $con_subarea->fetch_assoc()){
			echo '<option value="' . $rows_subarea['id_subarea'] .'|'.$rows_subarea['subarea'].'|'.$area[1].'">' . htmlentities($rows_subarea['subarea']) . '</option>';
		}
	}
}
?>
<!--

<select class="form-control" name="subarea" id="subarea">
	<option value="">Aguardando a seleção da Área...</option>
</select>

-->