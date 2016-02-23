<?php
/* @var $this PatientsController */
/* @var $model Patients */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'patients-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
)); ?>

 <div class="errorMessage" id="formResult"></div>
        <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>
     
    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->hiddenField($model,'id',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fio'); ?>
		<?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'fio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'homephone'); ?>
		<?php echo $form->textField($model,'homephone'); ?>
		<?php echo $form->error($model,'homephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gender'); ?>
        <?php echo $form->dropDownList($model,'gender',
            array('1' => 'Мужской', '2' => 'Женский'),
            array('prompt'=> 'Выберите пол', 'style'=>'width:150px')
            );
        ?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_birthday'); ?>
		<?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
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
		<?php echo $form->error($model,'date_birthday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adress'); ?>
		<?php echo $form->textField($model,'adress',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'adress'); ?>
	</div>

	<div class="row">
	    <?php echo $form->labelEx($model,'source_id');?>
        <?php
        	$listSources = CHtml::listData(Sources::model()->findAll(), 'id', 'title');
            echo $form->dropDownList($model,'source_id', $listSources, array('empty' => 'Выберите источник'));
        ?>
		<?php echo $form->error($model,'source_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('size'=>60,'maxlength'=>400)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div style="float:right;">
		<?php if($model->date_modifidied !== NULL) echo "Дата изменения данных: ".$model->date_modifidied; ?>
		<br>
		<?php if($model->date_added !== NULL && !$model->isNewRecord) echo "Дата добавления пациента: ".$model->date_added; ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

 <?php echo CHtml::ajaxSubmitButton('Save',CHtml::normalizeUrl(array('patients/MyAction','render'=>true)),
    array(
     'dataType'=>'json',
     'type'=>'post',
     'success'=>'function(data) {
         $("#AjaxLoader").hide();  
        if(data.status=="success"){
         $("#formResult").html("form submitted successfully.");
         $("#patients-form")[0].reset();
        }
         else{
        $.each(data, function(key, val) {
        $("#patients-form #"+key+"_em_").text(val);                                                    
        $("#patients-form #"+key+"_em_").show();
        });
        }       
     }',                    
     'beforeSend'=>'function(){                        
           $("#AjaxLoader").show();
     }'
    )); 
?>

<?php echo CHtml::ajaxSubmitButton(
	'Submit request',
	array('patients/MyAction'),
	array(
		'update'=>'#req_res02',
	)
);
?>

<div id="req_res02">...</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
