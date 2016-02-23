<?php
/* @var $this DiscountsController */
/* @var $model Discounts */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'discounts-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php // echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'size'); ?>
		<?php echo $form->textField($model,'size'); ?>
	 	<?php echo $form->dropDownList($model,'type',
            array('1' => 'ГРН', '2' => '%'),
            array('default' => 1, 'style'=>'width:70px')
        ); ?>
		<?php echo $form->error($model,'size'); ?>
	</div>

<!-- 	<div class="row">
		<?php /* echo $form->labelEx($model,'date_added'); ?>
		<?php echo $form->textField($model,'date_added'); ?>
		<?php echo $form->error($model,'date_added'); */ ?>
	</div> -->

<!-- 	<div class="row">
		<?php /* echo $form->labelEx($model,'date_modifidied'); ?>
		<?php echo $form->textField($model,'date_modifidied'); ?>
		<?php echo $form->error($model,'date_modifidied'); */?>
	</div> -->

	<div style="float:right;">
		<?php if($model->date_modifidied !== NULL) echo "Дата изменения скидки: ".$model->date_modifidied; ?>
		<br>
		<?php if($model->date_added !== NULL && !$model->isNewRecord) echo "Дата добавления скидки: ".$model->date_added; ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->