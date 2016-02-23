<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'patient_id'); ?>
		<?php echo $form->textField($model,'patient_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'doctor_id'); ?>
	    <?php echo $form->dropDownList($model,'doctor_id', $model->getDoctors(), array('empty' => 'Выберите доктора')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'affiliate_id'); ?>
		<?php echo $form->dropDownList($model, 'affiliate_id', $model->getAffiliate() ,array('prompt' => 'Выберите филиал', 'class'=>'affiliate')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'datetime_visit'); ?>
		<?php echo $form->textField($model,'datetime_visit'); ?>
	</div>

	<div class="row">
		<label>Продолжительность визита, мин.</label>
		<?php // echo $form->label($model,'duration'); ?>
		<?php // echo $form->labelEx($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
	</div>

	<!--
	<div class="row">
		<?php //echo $form->label($model,'date_added'); ?>
		<?php //echo $form->textField($model,'date_added'); ?>
	</div>

	<div class="row">
		<?php //echo $form->label($model,'date_modifidied'); ?>
		<?php //echo $form->textField($model,'date_modifidied'); ?>
	</div> !-->

	<div class="row">
		<?php echo $form->label($model,'status_record_id'); ?>
		<?php echo $form->dropDownList($model,'status_record_id', $model->getStatuses(), array('empty' => 'Выберите статус')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Искать'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->