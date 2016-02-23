
<?php 
	echo CHtml::dropDownList('work_place', '', 
		PatientsHistory::getAffiliateWorkingPlaces($affiliate_id), 
		array('empty' => 'Выберите место', 'id'=>'FastAddingPatient_work_place_id') 
	); 
?>