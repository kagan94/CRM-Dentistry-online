<?php
/* @var $this PatientsController */
/* @var $model Patients */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пациенты'=>array('admin'),
	$model->fio,
);

$this->menu=array(
	array('label'=>'Все пациенты', 'url'=>array('admin')),
	array('label'=>'Добавить нового пациента', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить пациента', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы подтверждаете удаление данного элемента?')),
);
?>

<h1>Просмотр пациента "<?php echo $model->fio; ?>"</h1>

<?php if(isset($model->id)): ?>
<div style="text-align:right; text-decoration:underline;">
    
    <a href="/patients/print/<?= $model->id; ?>" target="_blank" title="Печать карты о пациенте"><img src="/images/print.png" width="50"></a>
</div>
<?php endif; ?>

<div style="color:red; text-align:center; ">
	<?php
		if(strlen(trim($model->diseases)) > 0)
			echo "Аллергические реакции: " . $model->diseases . "<br>";

		if(strlen(trim($model->allergic_reactions)) > 0)
			echo "Перенесенные и сопутствующие заболевания: " . $model->allergic_reactions . "<br>";
	 ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'fio',
        array(
            'name' => 'phone',
            'value' => isset($model->phone) ? $model->phone : '' ,
            'visible'=>(trim($model->phone) != '') ? true : false ,
        ),
        array(
            'name' => 'homephone',
            'value' => isset($model->homephone) ? $model->homephone : '' ,
            'visible'=>(trim($model->homephone) != '') ? true : false ,
        ),
        array(
        	'name' => 'gender',
            'value' => $model->gender == 1 ? "Мужской" : ($model->gender == 2 ? "Женский" : "" ),
            'visible'=>($model->date_birthday != '') ? true : false ,
        ),
        array(
        	'name' => 'date_birthday',
            'value' => $model->date_birthday != '0000-00-00' ? $model->date_birthday : '' ,
            'visible'=>($model->date_birthday != '0000-00-00' && $model->date_birthday != '') ? true : false ,
        ),
        array(
        	'name' => 'email',
            'value' => $model->email,
            'visible'=>($model->email != '') ? true : false ,
        ),
        array(
        	'name' => 'adress',
            'value' => $model->adress,
            'visible'=>($model->adress != '') ? true : false ,
        ),
        array(
            'name' => 'source_id',
            'value' => isset($model->source_id) ? $model->source->title : '' ,
            'visible'=>($model->source_id != '') ? true : false ,
        ),
        array(
            'name' => 'comment',
            'value' => isset($model->comment) ? $model->comment : '' ,
            'visible'=>(trim($model->comment) != '') ? true : false ,
        ),
		// 'date_added',
		// 'date_modifidied',
	),
)); 
?>

<br>
<center>
    <b>Визиты пациента</b>
</center>

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'patients-history-grid',
    'dataProvider'=>$visitsProvider,
    'enableSorting'=>false,
    //'filter'=>$model,
    'columns'=>array(
        array(
            'name' => 'doctor_id',
            'value'=> 'isset($data->doctor_id) ? Doctors::getDoctorShortFIO($data->doctor->fio) : ""' ,
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
        array(
            'name' => 'affiliate_id',
            'value'=> 'isset($data->affiliate_id) ? PatientsHistory::getAffiliateName($data->affiliate) : ""' ,
        ),
        array(
            'name' => 'datetime_visit',
            'value'=> '$data->getShortDate($data->datetime_visit)',
        ),
        array(
            'name' => 'duration',
            'value'=> 'isset($data->duration) ? $data->getPrettyDuration($data->duration) : ""',
        ),
        array(
            'name' => 'status_record_id',
            'value'=> 'isset($data->status_record_id) ? $data->statusRecord->title : ""' ,
        ),
        array(
            'name' => 'total',
            'value'=> 'isset($data->total) ? $data->total : ""' ,
        ),
        // 'comment',
    ),
)); ?>



