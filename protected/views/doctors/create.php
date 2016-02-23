<?php
/* @var $this DoctorsController */
/* @var $model Doctors */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Врачи'=>array('admin'),
	'Добавить нового',
);

$this->menu=array(
	array('label'=>'Все врачи', 'url'=>array('admin')),
);
?>

<h1>Добавить нового врача</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>