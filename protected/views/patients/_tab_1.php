<?php
/* @var $this PatientsController */
/* @var $model Patients */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'teeth-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
    // 'action'=>$this->createUrl('patients/UpdatePatient'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
)); ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->hiddenField($model,'id',array('size'=>60,'maxlength'=>150,'id'=>'model-id')); ?>
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
        <?php echo $form->textField($model,'date_birthday', array('id'=>'date_birthday', 'onscroll'=>'return false')); ?>

        <script>
            $(function() {
             $( "#date_birthday" ).datetimepicker({
                <?php echo DatePickerSettings::getDateBirthdaySettings(); ?>
             });
            });
        </script>
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
        <?php echo $form->labelEx($model,'allergic_reactions'); ?>
        <?php echo $form->textArea($model,'allergic_reactions', array('size'=>60,'maxlength'=>500)); ?>
        <?php echo $form->error($model,'allergic_reactions'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'diseases'); ?>
        <?php echo $form->textArea($model,'diseases', array('size'=>60,'maxlength'=>500)); ?>
        <?php echo $form->error($model,'diseases'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'comment'); ?>
        <?php echo $form->textArea($model,'comment', array('size'=>60,'maxlength'=>400)); ?>
        <?php echo $form->error($model,'comment'); ?>
    </div>

	<div class="date_added_and_changing">
		<?php if($model->date_modifidied !== NULL) echo "Дата изменения данных: ".$model->date_modifidied; ?>
		<br>
		<?php if($model->date_added !== NULL && !$model->isNewRecord) echo "Дата добавления пациента: ".$model->date_added; ?>
	</div>

	<div class="row buttons">
     <?php 
     if($model->isNewRecord){
        // For New Patients 2 buttons: Submit and Save
     	echo CHtml::ajaxSubmitButton('Создать',
             array("patients/CreatePatient"),
             array(
                 'dataType'=>'json',
                 'type'=>'post',
                 'success'=>'function(data) {
                     $("#AjaxLoader").hide();

                    if(data.status=="success"){

                     $("ul li:nth-child(2)").removeClass("ui-state-disabled");
                     $("ul li:nth-child(3)").removeClass("ui-state-disabled");
                     $("ul li:nth-child(4)").removeClass("ui-state-disabled");
                     $("ul li:nth-child(5)").removeClass("ui-state-disabled");
                     $("#create").hide();
                     $("#save").show();
                     $("#formResult").attr("class","goodNotice");
                     $("#formResult").html("Пациент успешно добавлен.");
                     $("#model-id").val(data.patient_id);
                     $("#ToothHistory_patient_history_id").val(data.patient_id);
                     // $("#ToothHistory_patient_history_id").val($("#model-id").val());

                     // Для Patient History
                     var line = $("#ui-id-2").attr("href");
                     line = line.replace(/patient_id=[0-9]+/, "patient_id="+data.patient_id);
                     $("#ui-id-2").attr("href",line);

                     // Для денежных операций
                     var line2 = $("#ui-id-6").attr("href");
                     line2 = line2.replace(/patient_id=[0-9]+/, "patient_id="+data.patient_id);
                     $("#ui-id-6").attr("href",line2);
                    }
                     else
                    { 
                     $("#formResult").attr("class","errorNotice");
                     $("#formResult").html("В заполненных данных есть ошибки.");
                    }       
                }',                    
                 'beforeSend'=>'function(){                        
                       $("#AjaxLoader").show();
                  }',          
            ),
            array('id' => 'create', 'name' => 'create')
         );

         echo CHtml::ajaxSubmitButton('Сохранить',array("patients/UpdatePatient"),
             array(
                 'dataType'=>'json',
                 'type'=>'post',
                 'success'=>'function(data) {
                     $("#AjaxLoader").hide();
                    if(data.status=="success"){
                     $("#formResult").attr("class","goodNotice");
                     $("#formResult").html("Данные о пациенте успешно сохранены.");
                    }
                     else
                    {
                     $("#formResult").attr("class","errorNotice");
                     $("#formResult").html("В заполненных данных есть ошибки.");
                    }
                }',
                 'beforeSend'=>'function(){
                       $("#AjaxLoader").show();
                  }',
             ),
             array('id' => 'save', 'name' => 'save', 'style' => 'display:none;')
        );
     } else {
     	echo CHtml::ajaxSubmitButton('Сохранить',array("patients/UpdatePatient/{$model->id}"),
             array(
                'dataType'=>'json',
                'type'=>'post',
                'success'=>'function(data) {
                     $("#AjaxLoader").hide();  
                    if(data.status=="success"){
                     $("#formResult").attr("class","goodNotice");
                     $("#formResult").html("Данные о пациенте успешно сохранены.");
                    } 
                     else
                    { 
                     $("#formResult").attr("class","errorNotice");
                     $("#formResult").html("В заполненных данных есть ошибки.");
                    }       
                }',                    
                'beforeSend'=>'function(){                        
                    $("#AjaxLoader").show();
                }',
        ));
     }
    ?>
	</div>

    <br>
    <div style="display: none;text-align: center;" id="formResult">Данные о пациенте успешно сохранены.<br></div>
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>

<?php $this->endWidget(); ?>

</div><!-- form -->
