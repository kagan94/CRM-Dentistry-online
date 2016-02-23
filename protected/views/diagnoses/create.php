<?php
/* @var $this DiagnosesController */
/* @var $model Diagnoses */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Диагнозы'=>array('admin'),
	'Добавить новый',
);

$this->menu=array(
	array('label'=>'Все диагнозы', 'url'=>array('admin')),
);
?>

<h1>Добавить новый диагноз</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>