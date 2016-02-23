<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */
/* @var $form CActiveForm */

if(Yii::app()->request->isAjaxRequest) {
    Yii::app()->clientScript->scriptMap['*.js'] = false;
} else {

	echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.datetimepicker.js');
	echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/services_func.js');
    echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.qtip.min.js');

    $cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/jquery.datetimepicker.css');
    $cs->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.qtip.min.css');
}

	$show = 1;
	
	if(!isset($model->status_record_id) OR $model->status_record_id == 3) $show = 0;
?>

<script>
  $(function() {

  	<?php if($model->isNewRecord){ ?>
  	$('.make_payment').hide();
  	<?php } ?>

    $('.make_payment').click(function(event){
    	var $add_payment = $('#form_add_payment');
    	if($add_payment.is(':hidden')){
    		$add_payment.show(300);
    	} else {
    		$add_payment.hide(300);
    	}
    });

    $('#form_add_payment button').click(function(event){
		var errors = 0,
	        $selector = $('#form_add_payment'),
	        $date_visit = $selector.find('input[name="date"]'),
	        $sum = $selector.find('input[name="sum"]'),
	        $comment = $selector.find('textarea[name="comment"]');

		if($sum.val() <= 0 || isNaN($sum.val()) ) {
			alert('Введите правильную сумму оплаты!');
			errors+=1;
		}

		if($date_visit.val() == '') {
			alert('Выберите дату визита!');
			errors+=1;
		}

		if(errors == 0){
			$.ajax({
			  type: 'POST',
			  dataType: 'json',
			  data: {
			      patient_id: $('#PatientsHistory_patient_id').val(),
			      patient_history_id: $('#patient_history_id').val(),
			      date: $date_visit.val(),
			      sum: $sum.val(),
			      comment: $comment.val(),
			  },
			  url: '/action/transactions/add',
			  cache: false,
			  success: function(data){
			    if(data.status == "success"){
    				$selector.hide();
			    	$date_visit.val('');
			    	$sum.val('');
			    	$comment.val('');
			      	$('.current_balance').html(data.patient_balance);
			    } else {
			      $.each(data.errors, function(key, val) {
			        alert(val);                                                    
			      });
			    }
			  },
			});
		}
    });


  	$( "input[name=\"date\"]" ).datetimepicker({
 		<?php echo DatePickerSettings::getDateBirthdaySettings(); ?>
  	});

	$('.notice').qtip({
	    content: $('.notice').attr('title'),
	    position: {
	        my: 'top center',
	        at: 'bottom center',
	    }
	});

    $("#status_record select").change(function(){
    
        if($(this).val() == '' || $(this).val() == 3){
        	$('#table_services, #duration').animate({height: 'hide'}, 500); 
    	    $(".service_key").each(function(){
				$(this).val('');
			});     
        } else {
          $('#table_services, #duration').animate({height: 'show'}, 500); 
        }
    });

  });
</script>


<div style="color:red;">
	<?php
		if(isset($model->patient->diseases)){
			if(strlen(trim($model->patient->diseases)) > 0)
				echo "Аллергические реакции: " . $model->patient->diseases . "<br>";

			if(strlen(trim($model->patient->allergic_reactions)) > 0)
				echo "Перенесенные и сопутствующие заболевания: " . $model->patient->allergic_reactions . "<br>";
		}
	 ?>
</div>


<div class="form">
	
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'patients-history-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
));  ?>

    <p class="note">Поля отмеченные <span class="required">*</span> обязательные.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->error($model,'discount_id'); ?>

<div class="row">
	<?php if(!Yii::app()->request->isAjaxRequest) { ?>
	    <?php
	        echo $form->labelEx($model,'patient_id');

	        $this->widget('CAutoComplete',
	            array(
	                'model' => $model,
	                'attribute' => 'patient_fio',
	                'value' => CHtml::value($model, 'patient_fio'),
	                'url' => array('patient/History/findPatientFio'),
	                'max' => 10,
	                'minChars' => 1,
	                'delay' => 100,
	                'matchCase' => false,
	                'htmlOptions' => array('size' => '20'),

	                'methodChain' => ".result(function(event,item){\$(\"#PatientsHistory_patient_id\").val(item[1]);})",
	        )); ?>
	<?php } ?>

    <!-- Для модалки -->
    <?php echo $form->hiddenField($model,'patient_id'); ?>
    <?php echo $form->hiddenField($model,'id', array('id'=>'patient_history_id')); // <- Id Истории лечения ?>
	<?php echo $form->error($model,'patient_id'); ?>
</div>

	<div class="row" id="doc_id" >
		<?php 
		if(isset($model->doctor_id))
			if(!array_key_exists($model->doctor_id, $model->getDoctors()) )
				echo "<div class=\"red_msg\">! Доктор, который был закреплен за этим визитом либо перенесен в корзину, либо больше не работает !</div> ";
	    ?>

		<?php echo $form->labelEx($model,'doctor_id'); ?>
	    <?php echo $form->dropDownList($model,'doctor_id', $model->getDoctors(), array('empty' => 'Выберите доктора')); ?>
		<?php echo $form->error($model,'doctor_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'affiliate_id'); ?>
		<?php echo $form->dropDownList($model, 'affiliate_id', $model->getAffiliate() , array('prompt' => 'Выберите филиал', 'class'=>'affiliate', 'id'=>'PatientsHistory_affiliate_id')); ?>
		<?php echo $form->error($model,'affiliate_id'); ?>
	</div>

	<div id="check_work_place">
		<?php if(isset($model->affiliate_id)){ ?>
			<div class="row">
				<?php echo $form->labelEx($model,'work_place_id'); ?>
				<?php echo CHtml::dropDownList('PatientsHistory[work_place_id]', $model->work_place_id, PatientsHistory::getAffiliateWorkingPlaces($model->affiliate_id), array('empty' => 'Выберите место'));
				 ?>
				<?php echo $form->error($model,'work_place_id'); ?>
			</div>
		<?php } ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row" id="status_record">
		<?php echo $form->labelEx($model,'status_record_id'); ?>
        <?php echo $form->dropDownList($model,'status_record_id', $model->getStatuses(), array('empty' => 'Выберите статус')); ?>
		<?php echo $form->error($model,'status_record_id'); ?>
	</div>

	<div class="row">
        <?php echo $form->labelEx($model,'datetime_visit'); ?>
        <?php echo $form->textField($model,'datetime_visit',array('id'=>'timepicker')); ?>

		<script>
			$(function() {
			 $( "#timepicker" ).datetimepicker({
         		<?php echo DatePickerSettings::getVisitSettings('#timepicker'); ?>
			 });
			});
		</script>

		<?php echo $form->error($model,'datetime_visit'); ?>
	</div>

	<div class="row" id="duration" <?php if($show == 0) echo 'style="display:none;"'; ?>>
		<?php echo $form->labelEx($model,'duration'); ?>
        <?php echo $form->dropDownList($model,'duration',
            array("10"=>"10 мин.", "15"=>"15 мин.", "20"=>"20 мин.", "25"=>"25 мин.", "30"=>"30 мин.", "35"=>"35 мин.", "40"=>"40 мин.", "45"=>"45 мин.", "50"=>"50 мин.", "55"=>"55 мин.", "60"=>"1 час", "65"=>"1 час 5 мин.", "70"=>"1 час 10 мин.", "75"=>"1 час 15 мин.", "80"=>"1 час 20 мин.", "85"=>"1 час 25 мин.", "90"=>"1 час 30 мин.", "95"=>"1 час 35 мин.", "100"=>"1 час 40 мин.", "105"=>"1 час 45 мин.", "110"=>"1 час 50 мин.", "115"=>"1 час 55 мин.", "120"=>"2 часа"),
            array('prompt'=> 'Выберите продол.', 'style'=>'width:205px')
        );
        ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>

		<?php echo $form->hiddenField($model,'total'); ?>
		<?php echo $form->error($model,'total'); ?>

		<?php $number_row = 1; // Номер нового ряда услуг  ?>

<div id="table_services" <?php if($show == 0) echo 'style="display:none;"'; ?> >
	<div class="service_title">Услуги</div>

	<table class="table-services">
		<tr id="services" style="display:none">
		    <th width="40%">Наименование</th>
		    <th>Количество</th>
		    <th>Цена</th>
		    <th>Всего</th>
		    <th></th>
		</tr>
		<tr class="parameters" id="parameters" style="display:none;">
		    <td>
		        <div class="row">
					<?php echo Services::model()->getTreeServices(); // getTreeServices($current_service_id = '', $number_row = 0) ?>
		        </div>
		    </td>
		    <td>
		        <input type="number" maxlength="5" class="services" name="PatientsHistory[services][quantity][row_id_0]" id="service_quantity_0" value="1">
		        <input type="hidden" name="PatientsHistory[services][key][row_id_0]" id="service_key_0" class="service_key" value="0">
		    </td>
		    <td>
		        <div id="service_price_0"></div>
		    </td>
		    <td>
		        <div id="total_price_0" class="total_price"></div>
		    </td>
		    <td>
		        <button type="button" class="del-button" id="del_0" onclick="deleteRow(this.id); return false;">Удалить</button>
		    </td>
		</tr>


		<?php
		    if(!$model->isNewRecord && count($allPatientHisotoryServices) > 0 ) {
		        foreach ($allPatientHisotoryServices as $key=>$val) {
		 ?>

		<tr class="parameters" id="parameters_<?=$number_row ?>">
		    <td>
		        <div class="row">
				<?php
					echo Services::model()->getTreeServices($val['service_id'], $number_row); // getTreeServices($current_service_id = '', $number_row = 0)
		           
		            $find_service = Services::model()->FindByPk($val['service_id']);
		            if(count($find_service) > 0 ) {
		            	if($val['price'] != $find_service->price){
		            		echo '<img src="/images/info.png" class="notice" title="После обновления цена на данную услугу будет пересчитана. <br>';
		            		echo '<b>Старая цена</b> - '. $val['price'] .' грн. <br><b>Новая цена</b> - '. $find_service->price .' грн.">';
		            	}
		            } else {
		            	echo '<img src="/images/info.png" class="notice" title="Услуга <b>\''. $val['title'] .'\'</b> была удалена, и не будет доступна при обновлении визита.">';
		            }
	            ?>
		        </div>
		    </td>
		    <td>
		        <input type="number" maxlength="5" class="services" name="PatientsHistory[services][quantity][row_id_<?=$number_row ?>]" id="service_quantity_<?=$number_row ?>" value="<?php echo $val['quantity']; ?>">
		        <input type="hidden" name="PatientsHistory[services][key][row_id_<?=$number_row ?>]" id="service_key_<?=$number_row ?>" class="service_key" value="<?php echo $val['order_service_id']; ?>">
		    </td>
		    <td>
		        <div id="service_price_<?=$number_row ?>"><?php echo $model->getServicePrice($val['service_id']); ?></div>
		    </td>
		    <td>
		        <div id="total_price_<?=$number_row ?>" class="total_price"></div>
		    </td>
		    <td>
		        <button type="button" class="del-button" id="del_<?=$number_row ?>" onclick="deleteRow(this.id); return false;">Удалить</button>
		    </td>
		</tr>
		    <?php 	  $number_row++; 
		            }
		        }
		    ?>

		<tr id="discount_block" style="display:none;">
			<td colspan='2' style="text-align:right; padding-right:40px; ">
				Скидка: 
			</td>
			<td colspan='1'>
				<?php $model->getDiscountsList($model->discount_id); ?>
			</td>
			<td colspan='1' id="discount_amount">
				-
			</td>
		</tr>
		<tr id="total_services" style="display:none;">
			<td colspan='3' style="text-align:right;">
				<b>Сумма без скидки:</b>
			</td>
			<td colspan='1' id="sum_up">
				-
			</td>
		</tr>
		<tr id="total_with_discount" style="display:none;">
			<td colspan='3' style="text-align:right;">
				<b>Всего (в т.ч. со скидкой) :</b>
			</td>
			<td colspan='1' id="sum_up_total">
				-
			</td>
		</tr>
	</table>
	<p>
	    <button class="add-button">Добавить услугу</button>
	</p>

</div>

<script type="text/javascript">

    $("#PatientsHistory_patient_fio").on("input", function(){
      $('#PatientsHistory_patient_id').val("");
    });

    var cnt = <?=$number_row ?>;

	$("#PatientsHistory_affiliate_id").click(function(){

	    $.ajax({
	        success: function(html){
	            $("#check_work_place").html(html);      // выводим во вью наши поля.
	            // $("#check_work_place").find('.row').append('<div class="errorMessage" id="PatientsHistory_work_place_id_em_"></div>');
	        },
	        type: 'get',
	        url: '/patient/history/getworkplace',      // делаем ajax зарос к action
	        data: {
	            work_place_id: $("#PatientsHistory_affiliate_id :selected").val(),
	        },
	        cache: false,
	        dataType: 'html'
	    });
	});

	$('#discount').bind('change', function() {
		updateTotalPrice();
	});

    if(cnt>1){
        $('#total_services, #discount_block,  #services, #total_with_discount').show();

        for(var i=1;i<cnt;i++){
        	updatePrice(i);

	    	quantity = $('#service_quantity_'+i).val();
	    	serv_price = $('#service_id_'+ i + ' :selected').attr('data-price');
    		arr_check =[];

			if (parseInt($('#service_quantity_'+i).text(),10) > 0) {
				sum_up = sum_up + parseInt($('#service_quantity_'+i).text(),10);
			}

	    	if(quantity > 0 && serv_price > 0){
	    		$('#service_price_'+i).text(serv_price);
				$('#total_price_'+i).text(serv_price*quantity);

	    	} else {
	    		$('#service_price_'+i).text('0');
	    		$('#total_price_'+i).text('0');
	    	}

	    	updateTotalPrice();
        }
    }

    $('.add-button').click(function(event){
        $('#total_services, #discount_block, #services, #total_with_discount').show();

        event.preventDefault();
        var line = $('#parameters').html();
        var expr = [ /del_[0-9]+/, /total_price_[0-9]+/, /service_id_[0-9]+/, /service_quantity_[0-9]+/, /service_price_[0-9]+/, /row_id_[0-9]+/g, /service_key_[0-9]+/ ];
        var statement = [ 'del_', 'total_price_', 'service_id_', 'service_quantity_', 'service_price_', 'row_id_', 'service_key_'];

        for(var i=0; i<7; i++) {
            line = line.replace(expr[i], statement[i]+cnt);
        }

        $('<tr class="parameters" id="parameters_'+cnt+'">'+line+'</tr>').insertBefore('#discount_block');
        updatePrice(cnt);
        cnt++;
    });

    function disable_services(){
		$(".services, #discount, #PatientsHistory_status_record_id, #timepicker").attr('disabled', true);
		$(".add-button, .del-button").remove();
    }
	
	<?php if(!User::checkPermissionEditServices($model->datetime_visit)){ // Замораживаем если прошло 2 дня и юзер не админ. ?>
		disable_services();
	<?php } ?>

</script>

<?php if(!User::checkPermissionEditServices($model->datetime_visit)){ ?>
	<div class="flash-notice">Вы не можете изменять услуги в данном визите, так как с момента создания визита прошло более 2-х дней</div>
<?php } ?>

<!-- Платежи от пациента -->
	<div id="patient_balance" style="text-align:right;width: 100%;float: right;padding-top: 20px;">
		Текущий баланс: <div class="current_balance"><?php echo PatientBalance::getCurrentBalance($model->patient_id); ?></div>
	</div>
	<div class="make_payment">
	    Добавить оплату
	</div>
	<div id="form_add_payment" style="display: none;">
		<input type="text" name="date" maxlength="10" placeholder="Дата оплаты">
		<input type="text" name="sum" placeholder="Сумма">
		<textarea maxlength="250" name="comment" placeholder="Комментарий к оплате..."></textarea>
		<button onclick="return false;">Внести оплату.</button>
	</div>
	<div class="clearfix"></div>
<!-- Платежи от пациента -->

<div style="width: 100%; display: inline-flex; margin: 32px 0px 2px;">

<?php
if(!Yii::app()->request->isAjaxRequest) {
    echo CHtml::ajaxSubmitButton('Сохранить',
        array("patient/history/SavePatientHistory"),
        array(
            'dataType' => 'json',
            'type' => 'post',

            'success' => 'function(data) {
         	$("#AjaxLoaderTeeth").hide();
     	 
            if(data.status=="success"){
            	$("#result_of_saving").html("");
            	$("#patient_history_id").val(data.patient_history_id)
                $.each(data.successAddedServices, function(index, val) {
        	        var add_id = index.split("_");
                    $("#service_key_"+add_id["2"]).val(val).attr("value",val)
                });
                $.each(data.successDeletedServices, function(index, value) {
        	        var delete_id = value.split("_");
                    $("#service_id_"+delete_id["2"]).val("0").attr("value","0");
                    $("#service_price_"+delete_id["2"]+", #total_price_"+delete_id["2"]).text("0");
                    $("#parameters_"+delete_id["2"]).hide();
                });
    	    	updateTotalPrice();

    	    	if(data.disable_services == 1){
    	    		disable_services();
    	    	}

    	    	$(".make_payment").show();
    	    	$(".current_balance").html(data.patient_balance);

				$("#result_of_saving").attr("class","goodNotice");
				$("#result_of_saving").html("Данные успешно сохранены.<br>");
            } else {
            	$("#result_of_saving").show();
	            $("#result_of_saving").attr("class","errorNotice");
	            $("#result_of_saving").html("В заполненных данных есть ошибки.<br>");
		        $.each(data, function(ind, value) {
		             $("#result_of_saving").append("<br> "+value);
		         });
            }
        }',
            'beforeSend' => 'function(){
           $("#AjaxLoaderTeeth").show();
      }',
        ),
        array('id' => 'savePatientHistory', 'name' => 'savePatientHistory', 'style' => 'margin: 0 auto;')
    );

        echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/services_func.js');
} else { ?>

<br>
    <input id="savePatientHistory" name="savePatientHistory" style="margin: 0 auto;" type="submit" value="Сохранить">

<?php } ?>
</div>


<div style="display: none;text-align: center;" id="result_of_saving"></div>
<div id="AjaxLoaderTeeth" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>

<div class="date_added_and_changing">
	<?php if($model->date_modifidied !== NULL) echo "Дата изменения данных: ".$model->date_modifidied; ?>
	<br>
	<?php if($model->date_added !== NULL && !$model->isNewRecord) echo "Дата добавления истории лечения: ".$model->date_added; ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->