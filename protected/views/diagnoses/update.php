<?php
/* @var $this DiagnosesController */
/* @var $model Diagnoses */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Диагнозы'=>array('admin'),
	$model->title . ' / Изменить',
	//=>array('view','id'=>$model->id),
);

$this->menu=array(
	array('label'=>'Все диагнозы', 'url'=>array('admin')),
	array('label'=>'Добавить новый', 'url'=>array('create')),
);
?>

<h1>Обновить данные диагноза "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>