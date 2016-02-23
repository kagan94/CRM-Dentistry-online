<?php
/* @var $this PatientsController */
/* @var $model Patients */
echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/bootstrap-tooltip.js');
?>

<script>
    $(function() {
        $('[data-toggle="tooltip"]').click(function(){
            // Очистка полей
            $('#form-teeth-map input').removeAttr('checked');
            $('#form-teeth-map input[type="hidden"], #form-teeth-map input[type="text"]').val('');

            $("#form-teeth-map").show();
            $("#tooth-number").val( $(this).attr("tooth-number") ) ;
            $("#ToothHistory_patient_id").val($("#model-id").val());

            $.ajax({
                url: "/patients/GetPatientTeethStatus/",
                type: "POST",
                data: {'patient_id':$("#model-id").val(), 'tooth_number':$(this).attr("tooth-number")},

                dataType: 'json',  //Missed a comma over here
                success: function (data) {
                    if (data.status == 'success') {
                        $.each(data.tooth_statuses, function(index, value) {
                            $("#ToothHistory_status_id_"+index).prop("checked", true);
                            $("#ToothHistory_tooth_history_id_"+index).val(value);
                        });

                    }
                }
            });
        });

        $('[data-toggle="tooltip"]').click(function() {
           // $(this).removeClass("selected");
            $('[data-toggle="tooltip"]').removeClass("selected");
            $(this).addClass("selected");
        });
    });
</script>


<?php if(!$model->isNewRecord){ ?>
    <script>
        $(function() {
            tooth_number = <?php echo Patients::getAllStatuses($model->id); ?>;

            $.each(tooth_number, function(index, value) {
                $('[tooth-number='+index+'][data-toggle="tooltip"]').tooltip({
                    content: function() {
                        return value;
                    }
                });

                $('[tooth-number='+index+']').attr('title',value).addClass("teeth_info");
            });
        });
    </script>
<?php } ?>


<div class="tooth">
<table width="600" align="center" height="179" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="34">
            <img src="/images/zub_01.gif" width="600" height="5" alt=""></td>
    </tr>
    <tr>
        <td rowspan="4">
            <img src="/images/zub_02.gif" width="3" height="173" alt=""></td>
        <td colspan="2" tooth-number="18" data-toggle="tooltip">
            <img src="/images/zub_03.gif" width="39" height="68" border="0" alt=""></td>
        <td colspan="2" tooth-number="17" data-toggle="tooltip">
            <img src="/images/zub_04.gif" width="44" height="68" border="0" alt=""></td>
        <td colspan="2" tooth-number="16" data-html="true" data-toggle="tooltip">
            <img src="/images/zub_05.gif" width="38" height="68" alt=""></td>
        <td colspan="2" tooth-number="15" data-toggle="tooltip">
            <img src="/images/zub_06.gif" width="35" height="68" alt=""></td>
        <td colspan="2" tooth-number="14" data-toggle="tooltip">
            <img src="/images/zub_07.gif" width="33" height="68" alt=""></td>
        <td colspan="2" tooth-number="13" data-toggle="tooltip">
            <img src="/images/zub_08.gif" width="36" height="68" alt=""></td>
        <td colspan="2" tooth-number="12" data-toggle="tooltip">
            <img src="/images/zub_09.gif" width="30" height="68" alt=""></td>
        <td colspan="2" tooth-number="11" data-toggle="tooltip">
            <img src="/images/zub_10.gif" width="35" height="68" alt=""></td>
        <td rowspan="4">
            <img src="/images/zub_11.gif" width="5" height="173" alt=""></td>
        <td colspan="2" tooth-number="21" data-toggle="tooltip">
            <img src="/images/zub_12.gif" width="39" height="68" alt=""></td>
        <td colspan="2" tooth-number="22" data-toggle="tooltip">
            <img src="/images/zub_13.gif" width="29" height="68" alt=""></td>
        <td colspan="2" tooth-number="23" data-toggle="tooltip">
            <img src="/images/zub_14.gif" width="38" height="68" alt=""></td>
        <td colspan="2" tooth-number="24" data-toggle="tooltip">
            <img src="/images/zub_15.gif" width="34" height="68" alt=""></td>
        <td colspan="2" tooth-number="25" data-toggle="tooltip">
            <img src="/images/zub_16.gif" width="32" height="68" alt=""></td>
        <td colspan="2" tooth-number="26" data-toggle="tooltip">
            <img src="/images/zub_17.gif" width="39" height="68" alt=""></td>
        <td colspan="2" tooth-number="27" data-toggle="tooltip">
            <img src="/images/zub_18.gif" width="42" height="68" alt=""></td>
        <td tooth-number="28" data-toggle="tooltip">
            <img src="/images/zub_19.gif" width="41" height="68" alt=""></td>
        <td rowspan="4">
            <img src="/images/zub_20.gif" width="8" height="173" alt=""></td>
    </tr>
    <tr>
        <td colspan="16">
            <img src="/images/zub_21.gif" width="290" height="34" alt=""></td>
        <td colspan="15">
            <img src="/images/zub_22.gif" width="294" height="34" alt=""></td>
    </tr>
    <tr>
        <td rowspan="2">
            <img src="/images/zub_23.gif" width="5" height="71" alt=""></td>

        <td colspan="2" tooth-number="48" data-toggle="tooltip">
          <img src="/images/zub_24.gif" width="45" height="67" alt=""></td>
        <td colspan="2" tooth-number="47" data-toggle="tooltip">
            <img src="/images/zub_25.gif" width="44" height="67" alt=""></td>
        <td colspan="2" tooth-number="46" data-toggle="tooltip">
            <img src="/images/zub_26.gif" width="43" height="67" alt=""></td>
        <td colspan="2" tooth-number="45" data-toggle="tooltip">
            <img src="/images/zub_27.gif" width="33" height="67" alt=""></td>
        <td colspan="2" tooth-number="44" data-toggle="tooltip">
            <img src="/images/zub_28.gif" width="31" height="67" alt=""></td>
        <td colspan="2" tooth-number="43" data-toggle="tooltip">
            <img src="/images/zub_29.gif" width="33" height="67" alt=""></td>
        <td colspan="2" tooth-number="42" data-toggle="tooltip">
            <img src="/images/zub_30.gif" width="31" height="67" alt=""></td>
        <td tooth-number="41" data-toggle="tooltip">
            <img src="/images/zub_31.gif" width="25" height="67" alt=""></td>
        <td tooth-number="31" data-toggle="tooltip">
            <img src="/images/zyb_31.gif" width="30" height="67" alt=""></td>
        <td colspan="2" tooth-number="32" data-toggle="tooltip">
            <img src="/images/zub_33.gif" width="29" height="67" alt=""></td>
        <td colspan="2" tooth-number="33" data-toggle="tooltip">
            <img src="/images/zub_34.gif" width="34" height="67" alt=""></td>
        <td colspan="2" tooth-number="34" data-toggle="tooltip">
            <img src="/images/zub_35.gif" width="29" height="67" alt=""></td>
        <td colspan="2" tooth-number="35" data-toggle="tooltip">
            <img src="/images/zub_36.gif" width="36" height="67" alt=""></td>
        <td colspan="2" tooth-number="36" data-toggle="tooltip">
            <img src="/images/zub_37.gif" width="41" height="67" alt=""></td>
        <td colspan="2" tooth-number="37" data-toggle="tooltip">
            <img src="/images/zub_38.gif" width="47" height="67" alt=""></td>
        <td colspan="2" tooth-number="38" data-toggle="tooltip">
            <img src="/images/zub_39.gif" width="48" height="67" alt=""></td>
    </tr>
    <tr>
        <td colspan="15">
            <img src="/images/zub_40.gif" width="285" height="4" alt=""></td>
        <td colspan="15">
            <img src="/images/zub_41.gif" width="294" height="4" alt=""></td>
    </tr>
    <tr>
        <td>
            <img src="/images/spacer.gif" width="3" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="5" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="34" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="11" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="33" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="11" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="27" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="16" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="19" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="14" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="19" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="12" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="24" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="9" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="21" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="10" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="25" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="5" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="30" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="9" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="20" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="9" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="25" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="13" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="16" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="18" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="18" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="14" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="27" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="12" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="35" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="7" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="41" height="1" alt=""></td>
        <td>
            <img src="/images/spacer.gif" width="8" height="1" alt=""></td>
    </tr>
</table>
</div>


<div class="form" id="form-teeth-map" style="display: none;">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'patients-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
    )); ?>


    <?php echo $form->errorSummary($modelTeethMap); ?>
    <?php echo $form->error($modelTeethMap,'patient_id'); ?>

    <?php echo $form->hiddenField($modelTeethMap,'patient_id'); ?>
    <?php echo $form->hiddenField($modelTeethMap,'tooth_number',array('id'=>'tooth-number')); ?>


    <div style="margin: 0 auto; width: 790px;">
        <?php
        // Вывод статусов зуба
        $count = 1;
        foreach(Patients::getStatuses() as $id=>$title){
            if($count == 1) { echo "<div style='float: left;'>";}
                echo "<div class=\"row\" style=\"width: 250px; text-align: left !important;\">";
                echo $form->checkbox($modelTeethMap, "status_id[$id]", array('unchecked' => 0, 'value' => 1));
                echo "&nbsp;" . $title . "<br>";
                echo $form->hiddenField($modelTeethMap, "tooth_history_id[$id]");
                echo "</div>";

            if($count == 8) { echo "</div><div style='width: 250px; float: left;'>";}
            if($count == 15) { echo "</div><div style='width: 250px; float: left;'>";}
            if($count == 20) { echo "</div><div style='width: 200px; float: left;'>";}
            if($count == 20) { echo "</div>";}
            $count++;
        }
        ?>
    </div>


    <script>
        // Обновляем подсказки для изменнных данных
        function UpdateTooth() {
            $.ajax({
                url: "/patients/GetPatientTeethStatus/",
                type: "POST",
                data: {'patient_id': $("#model-id").val(), 'tooth_number': $("#tooth-number").val()},
                dataType: 'json',  //Missed a comma over here
                success: function (data) {
                    if (data.status == 'success') {
                        if (data.tooth_number == 0) {
                            $('[tooth-number=' + $("#tooth-number").val() + ']').attr('title', '').removeClass("teeth_info");;
                            $('[tooth-number=' + $("#tooth-number").val() + '][data-toggle="tooltip"]').tooltip({
                                content: function () {
                                    return '';
                                }
                            });
                        } else {
                            $.each(data.tooth_number, function (index, value) {
                                $('[tooth-number=' + index + '][data-toggle="tooltip"]').tooltip({
                                    content: function () {
                                        return value;
                                    }
                                });

                                $('[tooth-number=' + index + ']').attr('title', value).addClass("teeth_info");
                            });
                        }
                    }
                }
        });
        }
    </script>


    <div style="width: 100%; display: inline-flex; margin: 32px 0px 2px;"><?php
    echo CHtml::ajaxSubmitButton('Сохранить',
        array("patients/SaveTeethMap"),
        array(
            'dataType'=>'json',
            'type'=>'post',

            'success'=>'function(data) {
             $("#AjaxLoaderTeeth").hide();

            if(data.status=="success"){

             // Защита от повторного удаления/добавления
             $.each(data.arrAdded, function(key, value) {
                $("#ToothHistory_tooth_history_id_"+key).val(value);
             });
             $.each(data.arrDeleted, function(key, value) {
                $("#ToothHistory_tooth_history_id_"+value).val(\'\');
             });

             $("#formResult-SavingTeeth").attr("class","goodNotice");
             $("#formResult-SavingTeeth").html("Данные успешно сохранены.");

             UpdateTooth();
            }
             else
            {
             $("#formResult-SavingTeeth").attr("class","errorNotice");
             $("#formResult-SavingTeeth").html("В заполненных данных есть ошибки.");
            }
        }',
            'beforeSend'=>'function(){
               $("#AjaxLoaderTeeth").show();
          }',
        ),
        array('id' => 'saveTeethMap', 'name' => 'saveTeethMap', 'style'=>'margin: 0 auto;')
    );
    ?>
    </div>
    <?php $this->endWidget(); ?>

    <div style="display: none;text-align: center;" id="formResult-SavingTeeth">Данные о зубах пациента успешно сохранены.<br></div>
    <div id="AjaxLoaderTeeth" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>

</div>