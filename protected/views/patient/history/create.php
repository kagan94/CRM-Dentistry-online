<?php
/* @var $this HistoryController */
/* @var $model PatientsHistory */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'История лечений пациентов'=>array('admin'),
	'Добавить новую',
);

$this->menu=array(
	array('label'=>'Все истории', 'url'=>array('admin')),
);
?>

<h1>Добавить новую историю лечения</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>