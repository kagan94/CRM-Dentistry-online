<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'История лечений пациентов'=>array('admin'),
	/* 'Manage', */
);

$this->menu=array(
	array('label'=>'Добавить новую историю лечения', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#patients-history-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление историями лечений пациентов</h1>

<p>
    Также при поиске можно использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    или <b>=</b>).
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('/patient/history/_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'patients-history-grid',
	'dataProvider'=>$model->search(array('search_fio'=>true)),
	'filter'=>$model,
    'template'=> (Yii::app()->user->getName()=='admin')?"{summary}\n{items}\n{pager}":"{items}\n{pager}",
    'pager' => array(
       'maxButtonCount'=>'7',
    ),
	'columns'=>array(
		// 'id',
		array(
            'name' => 'patient_id',
            'value'=> 'isset($data->patient_id) ? $data->patient->fio : ""' ,
            'htmlOptions'=>array('style'=>'text-align:left;'),
            'sortable'=>false,
        ),
        array(
            'name' => 'doctor_id',
            'value'=> 'isset($data->doctor_id) ? Doctors::getDoctorShortFIO($data->doctor->fio) : ""' ,
            'htmlOptions'=>array('style'=>'text-align:left;'),
            'filter' =>  $model->getDoctors(),
        ),
        array(
            'name' => 'affiliate_id',
            'value'=> 'isset($data->affiliate_id) ? PatientsHistory::getAffiliateName($data->affiliate) : ""' ,
            'filter' => $model->getAffiliate(),
        ),
        array(
            'name' => 'datetime_visit',
            'value'=> '$data->getShortDate($data->datetime_visit)',
        ),
        array(
            'name' => 'status_record_id',
            'value'=> 'isset($data->status_record_id) ? $data->statusRecord->title : ""' ,
            'filter' =>  CHtml::listData(StatusRecord::model()->findAll(), 'id', 'title'), //$model->getStatuses(),
        ),
        array(
            'name' => 'comment',
            'htmlOptions'=>array('style'=>'text-align:left;'),
            'filter' =>  false,
        ),
        // array(
        //     'name' => 'total',
        //     'header'=> 'Стоимость, без учета скидки, грн.',
        //     //'value'=> 'isset($data->total) ? (int) ($data->total - $data->discount) : ""' ,
        //     // 'filter'=> false, 
        //     // 'sortable'=>false,
        // ),
        array(
            'name' => 'total2',
            'header'=> 'Стоимость, c учетом скидки, грн.',
            'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'template'    => (Yii::app()->user->getName()=='admin') ? '{update} &nbsp;{delete}' : '{update}',
            'deleteConfirmation'=>'Вы уверены что хотите УДАЛИТЬ ЭТОТ ВИЗИТ НАВСЕГДА?',
            'buttons'     => array(
                'update' => array(
                    'url'   => 'Yii::app()->createUrl("patient/history/update/", array("id"=>$data->id))',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/update.png',
                    'options'=>array(
                        'title'=>'Просмотр данных о визите.'
                    ),
                ),
                'delete' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/delete.png',
                ),
            ),
        ),
	),
));
?>
