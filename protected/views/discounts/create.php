<?php
/* @var $this DiscountsController */
/* @var $model Discounts */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Скидки'=>array('admin'),
	'Добавить новую',
);

$this->menu=array(
	array('label'=>'Все скидки', 'url'=>array('admin')),
);
?>

<h1>Добавить новую скидку</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>