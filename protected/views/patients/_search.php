<?php
/* @var $this PatientsController */
/* @var $model Patients */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'fio'); ?>
		<?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'homephone'); ?>
		<?php echo $form->textField($model,'homephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gender'); ?>
        <?php echo $form->dropDownList($model,'gender',
            array('1' => 'Мужской', '2' => 'Женский'),
            array('prompt'=> 'Выберите пол', 'style'=>'width:150px')
        );
        ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_birthday'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'model' => $model,
            'name' => 'Patients[date_birthday]',
            'value' => $model->date_birthday,

            'language'=>'ru',
            'options'=>array(
                'dateFormat'=>'yy-mm-dd',
                'language'=>'rus',
                'showAnim'=>'fold',
                'changeMonth'=>true,
                'changeYear'=>true,
                'yearRange'=>'1940:2015',
            ),
            'htmlOptions'=>array(
                'style'=>'height:20px;'
            ),
        ));
        ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'adress'); ?>
		<?php echo $form->textField($model,'adress',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_id'); ?>
		<?php echo $form->dropDownList($model,'source_id', $model->getSources(), array('empty' => 'Выберите источник')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Искать'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->