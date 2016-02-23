<?php
/* @var $this DoctorsController */
/* @var $model Doctors */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'doctors-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php // echo $form->errorSummary($model); ?>

<div class="a">
	<?php if ($model->trash == 1) {
		echo "<div class=\"red_msg\">! Данный врач находится в корзине !</div>";
	}
	?>
	<div class="row">
		<?php echo $form->labelEx($model,'fio'); ?>
		<?php echo $form->textField($model,'fio',array('size'=>30,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'fio'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'direction'); ?>
        <?php echo $form->textField($model,'direction',array('size'=>30,'maxlength'=>50)); ?>
        <?php echo $form->error($model,'direction'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	<hr>
	
	<div class="row" style="text-align: center; padding-left: 50px;">
		Укажите филиалы, в которых работает врач:
		<?php 
			$affs = Affiliate::model()->findAll(array('select' => 'id, concat("\"", name, "\" - ", city, " ", " (" , adress, ")") as name'));

			 echo $form->checkBoxList($model, 'affiliates', CHtml::listData($affs, 'id', 'name'), 
			  array(
			  	'separator'=>'',
			    'template'=>'<div class="checkbox_list">{input}&nbsp;{label}</div>')
			  );
		?>
	</div>

	<hr>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>
</div>

<div class="b">
	<?php echo $form->labelEx($model,'doctor_image', array('class'=>'doc_label')); ?>

	<?php echo $this->post_image($model->fio, $model->id.'.jpg', '150');?>
	<?php if(isset($model->photo) && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/doctors/'.$model->photo)){
			  echo $form->checkBox($model,'del_image',array('class'=>'span-1', 'style'=>'margin-left:35px;'));
		      echo "<span>Удалить?</span>";
		      //$form->labelEx($model,'del_image',array('class'=>'span-2'));
		  } 
	?>
	<br>
	<?php echo CHtml::activeFileField($model, 'doctor_image'); ?> 
	<?php echo $form->error($model,'doctor_image'); ?>
</div>
<div class="clearfix"></div>

<?php $this->endWidget(); ?>

</div><!-- form -->