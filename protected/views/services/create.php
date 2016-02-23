<?php
/* @var $this ServicesController */
/* @var $model Services */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Услуги'=>array('admin'),
	'Добавить новую услугу',
);

$this->menu=array(
	array('label'=>'Все услуги', 'url'=>array('admin')),
);
?>

<h1>Добавить новую услугу</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>