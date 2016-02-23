<?php
/* @var $this AffiliateController */
/* @var $model Affiliate */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'affiliate-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adress'); ?>
		<?php echo $form->textField($model,'adress',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'adress'); ?>
	</div>

	<div id="add_place">
	<?php
	if($model->isNewRecord):
		$places = 0;
	else:
		$places = WorkingPlaces::getWorkingPlacesByAffId($model->id);
	endif;

	if(count($places) > 0){
		for($i=1; $i<=count($places); $i++) { 
	?>
		<div class="row new-place" id="place_n<?php echo $i; ?>">
			<label for="WorkingPlaces_title">Название рабочего места</label>	
			<input maxlength="255" name="WorkingPlaces[title][<?php echo $i; ?>]" id="WorkingPlaces_title_<?php echo $i; ?>" value="<?=$places[$i-1]['title'];?>" type="text">
			<input name="WorkingPlaces[id][<?php echo $i; ?>]" value="<?=$places[$i-1]['id'];?>" type="hidden">		
			<button type="button" class="del-button" id="del_<?php echo $i; ?>" onclick="deleteRow(this.id); return false;">Удалить</button>
		</div>
	<?php 
		} 
	} else { 
	?>
		<div class="row new-place" id="place_n1">
			<label for="WorkingPlaces_title">Название рабочего места</label>	
			<input maxlength="255" name="WorkingPlaces[title][1]" id="WorkingPlaces_title_1" type="text">	
			<button type="button" class="del-button" id="del_1" onclick="deleteRow(this.id); return false;">Удалить</button>
		</div>
	<?php } ?>
	</div>

	<?php echo CHtml::button('Добавить рабочее место', array('class' => 'place-add')); ?>

	<script>
		$(".place-add").click(function(){
		    var index = $(".new-place").size()+1;       /// считаем  кол-во уже добавленных блоков с полями. 
		    $.ajax({
		        success: function(html){
		            $("#add_place").append(html);      // выводим во вью наши поля.
		        },
		        type: 'get',
		        url: '/affiliate/field',      // делаем ajax зарос к action
		        data: {
		            index: index
		        },
		        cache: false,
		        dataType: 'html'
		    });
		});


	    function deleteRow(btn_id){
	        var del_id = btn_id.split('_');
	        $('#place_n'+del_id[1]).hide();
	        $('#WorkingPlaces_title_'+del_id[1]).val('').attr('value','');
	    }
	</script>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->