<?php
/* @var $this ServicesController */
/* @var $model Services */
/* @var $form CActiveForm */
?>

<div class="form">

<script>
  $(function() {
    $("#service_id").change(function(){
        
        if($(this).val() == 0){
          $('#price_val, #code_val').val('');
          $('#price, #code').hide();
        } else {
          $('#price, #code').show();
        }

    });
  });
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'services-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row" id="code" <?php if($model->parent_id == 0) echo "style=\"display:none;\""; ?> >
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>10, 'maxlength'=>10, 'id'=>'code_val')); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row" id="price" <?php if($model->parent_id == 0) echo "style=\"display:none;\""; ?> >
		<?php echo $form->labelEx($model,'price_uah'); ?>
		<?php echo $form->textField($model,'price', array('id'=>'price_val')); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
	    <?php echo $form->labelEx($model,'parent_id');?>
        <?php
	        $criteria = new CDbCriteria;
	        $criteria->condition = "parent_id = 0";
			$criteria->order = 'title';

        	$listServices = CHtml::listData(Services::model()->findAll($criteria), 'service_id', 'title');
            echo $form->dropDownList($model,'parent_id', $listServices, array('empty' => 'Выберите', 'id'=>'service_id' ));
        ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<div style="float:right;">
		<?php if($model->date_modifidied !== NULL) echo "Дата изменения услуги: ".$model->date_modifidied; ?>
		<br>
		<?php if($model->date_added !== NULL && !$model->isNewRecord) echo "Дата добавления услуги: ".$model->date_added; ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->