<?php
	$places = PatientsHistory::getAffiliateWorkingPlaces($affiliate_id);

	if(count($places)):
?>

	<div class="row">
		<?php echo CHtml::label('Рабочее место *', 'PatientsHistory[work_place_id]'); ?>

		<?php echo CHtml::dropDownList('PatientsHistory[work_place_id]', '', $places, array('empty' => 'Выберите место'));
		 ?>
	</div>
<?php
	endif;
?>