<?php
/* @var $this DiscountsController */
/* @var $model Discounts */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'type'); ?>
	    <?php echo $form->dropDownList($model,'type',
            array('1' => 'В грн', '2' => 'В %'),
            array('prompt'=> 'Выберите тип скидки', 'style'=>'width:170px')
        );
        ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'size'); ?>
		<?php echo $form->textField($model,'size'); ?>
	</div>

	<!-- <div class="row">
		<?php /*echo $form->label($model,'date_added'); ?>
		<?php echo $form->textField($model,'date_added'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_modifidied'); ?>
		<?php echo $form->textField($model,'date_modifidied'); */?>
	</div> -->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Искать'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->