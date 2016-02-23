<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'История лечений пациентов'=>array('admin'),
	$model->patient->fio=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Все истории лечений пациентов', 'url'=>array('admin')),
	array('label'=>'Добавить новую', 'url'=>array('create')),
);
?>

<h1>Обновить историю лечения пациентов "<?php echo $model->patient->fio; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'allPatientHisotoryServices'=>$allPatientHisotoryServices)); ?>