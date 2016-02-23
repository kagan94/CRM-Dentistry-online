<?php
/* @var $this PatientsController */
/* @var $model Patients */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пациенты'=>array('admin'),
);

$this->menu=array(
	array('label'=>'Добавить нового пациента', 'url'=>array('create')),
);


echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.datetimepicker.js');

$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/jquery.datetimepicker.css');

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
'id' => 'mydialog',
        'options' => array(
            'title' => 'Отправить сообщение',
            'autoOpen' => false,
            'modal' => true,
            'resizable'=> false,
        ),
));
$this->endWidget('zii.widgets.jui.CJuiDialog');


Yii::app()->clientScript->registerScript('search', "

    $('.search-button').click(function(){
    	$('.search-form').toggle();
    	return false;
    });

    $('.search-form form').submit(function(){
    	$('#patients-grid').yiiGridView('update', {
    		data: $(this).serialize()
    	});
    	return false;
    });
    
      // Закрытие модалки при клике в пустое место
    $('body').on('click', '.ui-widget-overlay', function() {
       $('#mydialog').dialog('close');
    });
");
?>

<h1>Управление пациентами</h1>

<p>
    Также при поиске можно использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    или <b>=</b>).
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'patients-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'template'=> (Yii::app()->user->getName()=='admin')?"{summary}\n{items}\n{pager}":"{items}\n{pager}",
    'pager' => array(
       'maxButtonCount'=>'7',
       // 'firstPageLabel'=>'<<',
       // 'prevPageLabel'=>'<',
       // 'nextPageLabel'=>'>',
       // 'lastPageLabel'=>'>>',
       // 'header'=>'<span>Листалочка страничек:</span>',
       // 'cssFile'=>false,
    ), 
    'afterAjaxUpdate'=>"function() {  
        jQuery('#datepicker_for_valid_to').datepicker(jQuery.extend(
            jQuery.datepicker.regional['".Yii::app()->language."'], 
            {'showAnim':'fold','dateFormat':'yy-mm-dd', closeText: 'Очистить', 
                onClose: function (dateText, inst) {
                if ($(window.event.srcElement).hasClass('ui-datepicker-close'))
                {
                    document.getElementById(this.id).value = '';
                }
            }, 
        'changeMonth':'true','showButtonPanel':'true','changeYear':'true'})
    );}",
	'columns'=>array(
        array(
            'name'  => 'fio',
            'value' => '$data->fio',
            'htmlOptions'=>array('style'=>'text-align:left;min-width:200px;'),
        ),
		'phone',
        'homephone',
        array(
            'name' => 'date_birthday',
            'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker',
                array(
                    'htmlOptions' => array(
                        'id' => 'datepicker_for_valid_to',
                        'size' => '10',
                    ),
                    'model'=>$model,
                    'attribute'=>'date_birthday',
                    'language'=>''.Yii::app()->language.'',
                    'options'=>array(
                       'showAnim'=>'fold',
                       'dateFormat'=>'yy-mm-dd',
                       'changeMonth' => 'true',
                       'yearRange'=>'1940:2015',
                       'changeYear'=>'true',
                       'showButtonPanel' => 'true',
                    ),
                 ), true 
                ),
            'value' => '$data->date_birthday != "0000-00-00" ? "$data->date_birthday" : "" ',
        ),
       	array(
            'name'  => 'source_id',
            'value' => '($data->source_id != 0) ? $data->getSource($data->source_id) : ""',
            'filter'=> $model->getSources(),
        ),
       	array(
            'name'  => 'gender',
            'value' => '$data->gender == 1 ? "Мужской" : ($data->gender == 2 ? "Женский" : "" ) ',
            'filter'=>CHtml::dropDownList('Patients[gender]', $model->gender,
                array(
                    '1' => 'Мужской',
                    '2' => 'Женский',
                ),
                array('empty'=> '')
            ),
        ),
        array(
            'header'=>'Визиты',
            'name'=>'patient_hist_count',
            'value' => '($data->patient_hist_count>0)? $data->patient_hist_count : ""',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'    => (Yii::app()->user->getName()=='admin') ? '{view} &nbsp;{update} &nbsp;{delete}' : '{view} &nbsp;{update}',
            'deleteConfirmation'=>'Вы уверены что хотите УДАЛИТЬ этого пациента навсегда?',
            'buttons'     => array(
                'view' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/view.png',
                    'label'=>'Быстрый просмотр данных пациента',
                    'url' => 'Yii::app()->createUrl("patients/view", array( "id" => $data->id ) )',
                    'click'=>'function(){
                        $("#mydialog").dialog({
                            "title":"Просмотр данных о пациенте",
                            "autoOpen":false,
                            "modal":true,
                            "width":"900",
                            "height":"500",
                            "resizable":"false",
                        }).load($(this).attr("href")).dialog("open");

                        return false;
                    }',
                ),
                'update' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/update.png',
                ),
                'delete' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/delete.png',
                ),
            ),

        ),
	),
));

Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        //$.datepicker.setDefaults( $.datepicker.regional[ 'ru' ] );
        $('#datepicker_for_valid_to').datepicker();
    }
");
?>
