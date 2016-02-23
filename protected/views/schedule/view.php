<?php
    echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/fullcalendar/ru.js');
    echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.qtip.min.js');
    echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.datetimepicker.js');
    
    $cs = Yii::app()->clientScript;
    $cs->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.qtip.min.css');
    $cs->registerCssFile(Yii::app()->request->baseUrl.'/js/jquery.datetimepicker.css');
?>

<br>
<div>
    <input class="button_add_patient_quickly" name="yt0" type="button" value="Быстрое добавление пациента" id="add_patient_quickly">
</div>

<div class="search_schedule">
    <?php 
    echo CHtml::beginForm('/schedule/','get');
        // Выведет врачей которые не находятся в корзине 
    echo CHtml::dropDownList('doctor', $data['doctor'], PatientsHistory::getDoctors(), array('empty' => 'Выбирете врача'));
    echo CHtml::dropDownList('affiliate', $data['affiliate'], PatientsHistory::getAffiliate(), array('empty' => 'Выбирете филиал'));
    ?>
    <div id="check_work_place_in_search">
        <?php if(isset($data['affiliate'])){ ?>
            <?php echo CHtml::dropDownList('work_place', $data['work_place'], PatientsHistory::getAffiliateWorkingPlaces($data['affiliate']), array('empty' => 'Выберите место')); ?>
        <?php } ?>
    </div>
    <?php
        echo CHtml::submitButton('Искать', array('class'=>'search')); 
        echo CHtml::endForm();
    ?>
</div>
<br>

<?php
$this->widget('ext.fullcalendar.EFullCalendarHeart', array(
    'options'=>array(
        'timeFormat'=>'H:mm',
        'firstDay'=>'1',
        'header'=>array(
            'left'=>'month,agendaWeek,agendaDay',
            'center'=>'title',
            'right'=>'today,prev,next',
        ),
        //'defaultDate'=>'2015-08-01',
        // 'eventLimit'=>true, // for all non-agenda views
        // 'views'=> array(
        //     'agenda'=> array(
        //         'eventLimit' => '2', // adjust to 6 only for agendaWeek/agendaDay
        //      ),
        // ),
        // 'limitEvents'=>2,
        'allDaySlot'=>false,
        'hiddenDays'=> array(0),

        'height'=>'500',//'500',
        'lang'=>'ru',

        'minTime'=>"09:00:00",
        'maxTime'=>"22:00:00",
        'defaultView'=> 'agendaWeek',//'agendaWeek', month
        'axisFormat'=> 'HH:mm',

        'editable'=>false,
        'selectable' => true, 

        'events'=> array(
            'url'=> $data["events_url"],
        ), 

        'eventRender' => new CJavaScriptExpression('function(event, element) {
            $(this).attr("data_url", event.data_url);
             element.qtip({
                content: event.description,
                position: {
                    my: \'top center\',
                    at: \'bottom center\',
                }
             });
        }'),

        'eventClick' => new CJavaScriptExpression('function(calEvent, jsEvent, view) {
            // console.log(calEvent);
            // console.log(jsEvent);
            // console.log(this.href);

            editPatientVisit(this.href, calEvent.title);
            return false;
        }'),

        'dayClick' => new CJavaScriptExpression('function(date, allDay, jsEvent, view) {

            var selected_date = date.format("YYYY-MM-DD HH:mm");
            
            if( date.format("HH") == "00" ){
                // Если выбран месяц, меняем на 9:00
                selected_date = date.format("YYYY-MM-DD") + " " + "09:" + date.format("mm");
            }
            
                // Инициируем диалоговое окно
            $("#add_patient_quickly").trigger("click");

                // Подставляем дату в поле дата-время визита
            $("#FastAddingPatient_datetime_visit").val( selected_date );
        }'),
        // 'events'=>'[{"title":"Meeting","start":"2015-10-09","color":"#CC0000","allDay":true,"url":"http:\/\/anyurl.com"}]',
    ),
)); ?>


<!-- МОДАЛКА ДЛЯ ДОБАВЛЕНИЯ ВИЗИТА НАЧАЛО-->

    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'add_new_visit_popup',
        'options'=>array(
            'autoOpen'=>false,
            'modal'=>true,
        ),
    )); ?>

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'fast_adding_patient',
            'htmlOptions'=>array(
                'style'=>'display:none;',
            ),
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
        )); ?>
            <br>
            <?php echo $form->errorSummary($new_visit); ?>

            <div class="row">
                <?php echo $form->labelEx($new_visit,'patient_fio'); ?>
                <?php $this->widget('CAutoComplete',
                    array(
                        'model' => $new_visit,
                        'attribute' => 'patient_fio',
                        'value' => CHtml::value($new_visit, 'patient_fio'),
                        'url' => array('patient/History/findPatientFio'),
                        'max' => 10,
                        'minChars' => 1,
                        'delay' => 100,
                        'matchCase' => false,
                        'htmlOptions' => array('size' => '20'),

                        'methodChain' => ".result(function(event,item){\$(\"#FastAddingPatient_patient_id\").val(item[1]);})",
                ));
                ?>
                <?php echo $form->hiddenField($new_visit,'patient_id'); ?>

                <?php echo $form->labelEx($new_visit,'new_patient'); ?>
                <?php echo $form->checkbox($new_visit, "new_patient", array('unchecked' => 0, 'value' => 1, 'id' => 'is_new_patient')); ?>
                <?php echo $form->error($new_visit,'patient_fio'); ?>
            </div>

            <div class="row" id="phone" style="display:none;">
                <?php echo $form->labelEx($new_visit,'phone'); ?>
                <?php echo $form->textField($new_visit,'phone'); ?>
                <?php echo $form->error($new_visit,'phone'); ?>
            </div>

            <div class="row" id="date_birthday" style="display:none;">
                <?php echo $form->labelEx($new_visit,'date_birthday'); ?>
                <?php echo $form->textField($new_visit,'date_birthday', array('id'=>'FastAddingPatient_date_birthday')); ?>
                <?php echo $form->error($new_visit,'date_birthday'); ?>
            </div>

            <div class="row" id="doc_id" >
                <?php echo $form->labelEx($new_visit,'doctor_id'); ?>
                <?php echo $form->dropDownList($new_visit,'doctor_id', PatientsHistory::getDoctors(), array('empty' => 'Выберите доктора')); ?>
                <?php echo $form->error($new_visit,'doctor_id'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($new_visit,'affiliate_id'); ?>
                <?php echo $form->dropDownList($new_visit, 'affiliate_id', PatientsHistory::getAffiliate() , array('prompt' => 'Выберите филиал', 'class'=>'affiliate', 'id'=>'FastAddingPatient_affiliate_id')); ?>
                <?php echo $form->error($new_visit,'affiliate_id'); ?>
            </div>
            <div id="check_work_place_fast_adding"></div>

            <div class="row">
                <?php echo $form->labelEx($new_visit,'datetime_visit'); ?>
                <?php echo $form->textField($new_visit,'datetime_visit'); ?>
                <?php echo $form->error($new_visit,'datetime_visit'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($new_visit,'comment'); ?>
                <?php echo $form->textArea($new_visit,'comment', array('size'=>60,'maxlength'=>400)); ?>
                <?php echo $form->error($new_visit,'comment'); ?>
            </div>

            <br>
            <div style="text-align: center;">
                <?php 
                echo CHtml::ajaxSubmitButton('Добавить визит',CHtml::normalizeUrl(array('schedule/addnewvisit','render'=>true)),
                 array(
                     'dataType'=>'json',
                     'type'=>'post',
                     'success'=>'function(data) {
                        $("#AjaxLoader").hide();  
                        $("#fast_adding_patient input[type=\"submit\"]").attr("disabled", false);
                        
                        if(data.status=="success"){

                         $("#result").html("Визит успешно добавлен.").addClass("goodNotice").animate({height: "show"}, 500); 
                         $("#fast_adding_patient")[0].reset();
                         $("#FastAddingPatient_patient_id").val("");
                         hideInputsInForm();

                            // Присвоение нового события в календарь 
                         // var add_new_patient_visit = new Object();

                         // add_new_patient_visit.title       = data.patient.title; // this should be string
                         // add_new_patient_visit.description = data.patient.description; // this should be string
                         // add_new_patient_visit.start       = new Date(data.patient.start); // this should be date object
                         // add_new_patient_visit.end         = new Date(data.patient.end); // this should be date object
                         // add_new_patient_visit.color       = data.patient.color;
                         // add_new_patient_visit.url         = data.patient.url;

                         // var new_event = new Array();
                         // new_event[0] = add_new_patient_visit;

                         // $("#yw0").fullCalendar("addEventSource", new_event);

                        } else{
                         $("#result").html("В заполненых данных есть ошибки.").addClass("errorNotice").animate({height: "show"}, 500); 
                         $.each(data, function(key, val) {
                          $("#fast_adding_patient #"+key+"_em_").text(val);                                                    
                          $("#fast_adding_patient #"+key+"_em_").show();
                         });
                        }       
                    }',                    
                    'beforeSend'=>'function(){
                        $(".errorMessage, #result").html("").hide().removeClass("errorNotice").removeClass("goodNotice");
                        $("#AjaxLoader").show();
                        
                        $("#fast_adding_patient input[type=\"submit\"]").attr("disabled", true);
                    }'
                    )
                ); 
                ?>
            </div>

            <div id="AjaxLoader" style="display: none;">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img>
            </div>
            <div id="result" style="margin:20px; text-align:center;"></div>

        <?php $this->endWidget(); ?>

    <?php $this->endWidget(); ?>
<!-- МОДАЛКА ДЛЯ ДОБАВЛЕНИЯ ВИЗИТА КОНЕЦ-->


<script>

function hideInputsInForm(){
    $("#AjaxLoader, #fast_adding_patient #phone, #fast_adding_patient #date_birthday").hide();
}

$(function() {
    $('#add_patient_quickly').click(function() {
           // показываем скрытую форму
        $("#fast_adding_patient").show();
        
        $("#add_new_visit_popup").dialog({
            "title":"Добвление нового визита (истории лечения)",
            "autoOpen":false,
            "modal":true,
            "width":"960",
            "height":"500",
            "resizable":"false",
            "close":function(){
                $("#fast_adding_patient")[0].reset();
                $(".errorMessage, #result").html("").hide().removeClass("errorNotice").removeClass("goodNotice");
                $("#check_work_place_fast_adding").html("");
                $('#FastAddingPatient_patient_id').val("");

                  // Обновим календарь
                $("#yw0").fullCalendar('refetchEvents');

                hideInputsInForm();
            },
        }).dialog('open');
        return false;
    });

        // Закрытие модалки, по клику на оверлей
    $('body').on("click", ".ui-widget-overlay", function() {
       $('#add_new_visit_popup').dialog("close");
       $(".errorMessage, #result").html("").hide().removeClass("errorNotice").removeClass("goodNotice");
    });


    $("#affiliate").change(function(){

        $.ajax({
            success: function(html){
                $("#check_work_place_in_search").html(html);      // выводим во вью наши поля.
            },
            type: 'get',
            url: '/schedule/getworkplace',      // делаем ajax зарос к action
            data: {
                aff_id: $("#affiliate :selected").val(),
            },
            cache: false,
            dataType: 'html'
        });
    });
});
</script>

<script type="text/javascript"> // For modal window
    $(function() {
            // Зачистка id при изменении ФИО
        $("#FastAddingPatient_patient_fio").on("input", function(){
          $('#FastAddingPatient_patient_id').val("");
        });
            // Операции с чекбоксом 
        div_form = $('#fast_adding_patient');

        div_form.find('#is_new_patient').change(function() {
            if($(this).is(":checked")) {
                div_form.find('#phone, #date_birthday').animate({height: 'show'}, 500); 
                // div_form.find('').animate({height: 'show'}, 500); 
            } else {
                $("#FastAddingPatient_phone").val("");  // Обнулим поле телефона, для избежания ошибок
                div_form.find('#phone, #date_birthday').animate({height: 'hide'}, 500); 
                // div_form.find('#').animate({height: 'hide'}, 500); 
            }  
        });

        $("#FastAddingPatient_affiliate_id").click(function(){
            $.ajax({
                success: function(html){
                    $("#check_work_place_fast_adding").html(html);      // выводим во вью наши поля.
                    $("#check_work_place_fast_adding").find('.row').append('<div class="errorMessage" id="FastAddingPatient_work_place_id_em_" style="display:none"></div>');
                },
                type: 'get',
                url: '/patient/history/getworkplace',      // делаем ajax зарос к action
                data: {
                    work_place_id: $("#FastAddingPatient_affiliate_id :selected").val(),
                },
                cache: false,
                dataType: 'html'
            });
        });

        $( "#FastAddingPatient_date_birthday" ).datetimepicker({
            <?php echo DatePickerSettings::getDateBirthdaySettings(); ?>
        });

        $( "#FastAddingPatient_datetime_visit" ).datetimepicker({
            <?php echo DatePickerSettings::getVisitSettings('#FastAddingPatient_datetime_visit'); ?>
        });
    });
</script>


<!-- МОДАЛКА ДЛЯ РЕДАКТИРОВАНИЯ ВИЗИТА НАЧАЛО-->
    <script type="text/javascript"> // For modal window
        function editPatientVisit(url, patient_fio) {

            $("#edit_visit_popup").load(url).dialog({
                "title":"Изменение истории лечения пациента \"" + patient_fio + "\"",
                "autoOpen":false,
                "modal":true,
                "width":"960",
                "height":"580",
                "resizable":"false",
                "close":function(){
                      // Обновим календарь
                    $("#yw0").fullCalendar('refetchEvents');
                },
            }).dialog('open');

            return false;
        };

           //  Закрытие модалки, по клику на оверлей
        $('body').on("click", ".ui-widget-overlay", function() {
            $('#edit_visit_popup').dialog("close");
        });
    </script>

    <?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/services_func.js'); ?>
    <?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/ajax/save_patient_history.js'); ?>

    <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'edit_visit_popup',
        'options'=>array(
            'autoOpen'=>false,
            'modal'=>true,
        ),
    )); ?>
    <?php $this->endWidget(); ?>
<!-- МОДАЛКА ДЛЯ РЕДАКТИРОВАНИЯ ВИЗИТА КОНЕЦ-->


<!-- МОДАЛКА ДЛЯ ПОИСКА ВИЗИТОВ ПО ПАРАМЕТРАМ НАЧАЛО-->
    <script type="text/javascript">
        $('.search_schedule .search').on("click", function() {
            //             $('#yw0').fullCalendar({

            // events: [
            //         {
            //             title: 'Event1',
            //             start: '2011-04-04'
            //         },
            //         {
            //             title: 'Event2',
            //             start: '2011-05-05'
            //         }
            //         // etc...
            //     ],
            //             });
            // $('#yw0').fullCalendar('refetchEvents')
            // console.log($('#yw0').fullCalendar());
            // 'url'=> $this->createUrl('schedule/getAllVisits'),
            // 'data'=> new CJavaScriptExpression('{
            //     "doctor":     $(".search_schedule #doctor").val(),
            //     "affiliate":  $(".search_schedule #affiliate").val(),
            //     "work_place": $(".search_schedule #work_place").val(),
            // }'),

            // $("#yw0").fullCalendar('refetchEvents');
            // return false;
        });
    </script>
<!-- МОДАЛКА ДЛЯ ПОИСКА ВИЗИТОВ ПО ПАРАМЕТРАМ КОНЕЦ-->