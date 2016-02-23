<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'История лечений пациентов'=>array('admin'),
   $model->patient->fio,
);

$this->menu=array(
	array('label'=>'Все истории пациентов', 'url'=>array('admin')),
	array('label'=>'Добавить новую', 'url'=>array('create')),
	array('label'=>'Изменить историю пациента', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить эту историю', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы подтверждаете удаление данного элемента?')),
);
?>

<h1>Просмотр истории лечения, пациента "<?php echo $model->patient->fio; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
    array(
            'name' => 'patient_id',
            'value'=> isset($model->patient_id) ? $model->patient->fio : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
     array(
            'name' => 'doctor_id',
            'value'=> isset($model->doctor_id) ? $model->doctor->fio : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
     array(
            'name' => 'affiliate_id',
            'value'=> isset($model->affiliate_id) ? $model->affiliate->name . $model->affiliate->city . $model->affiliate->adress : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
     array(
            'name' => 'datetime_visit',
            'value'=> isset($model->datetime_visit) ? $model->datetime_visit : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
     array(
            'name' => 'duration',
            'value'=> isset($model->duration) ? $model->duration.' мин.' : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
    'comment',
     array(
            'name' => 'status_record_id',
            'value'=> isset($model->status_record_id) ? $model->statusRecord->title : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
     array(
            'name' => 'total',
            'value'=> isset($model->total) ? $model->total : "",
            'htmlOptions'=>array('style'=>'text-align:left;'),
     ),
		// 'patient_id',
		// 'doctor_id',
		// 'affiliate_id',
		// 'datetime_visit',
		// 'duration',
		// 'date_added',
		// 'date_modified',
		// 'status_record_id',
		// 'total',
	),
)); 
?>
