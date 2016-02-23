<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пользователи'=>array('admin'),
	'Создать нового',
);

$this->menu=array(
	array('label'=>'Все пользователи', 'url'=>array('admin')),
);
?>

<h1>Создание нового пользователя</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>