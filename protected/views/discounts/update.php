<?php
/* @var $this DiscountsController */
/* @var $model Discounts */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Скидки'=>array('admin'),
	$model->title . ' / Изменить',
	
);

$this->menu=array(
	array('label'=>'Все скидки', 'url'=>array('admin')),
	array('label'=>'Добавить новую скидку', 'url'=>array('create')),
);
?>

<h1>Обновить данные скидки "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>