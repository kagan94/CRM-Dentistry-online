<?php
/* @var $this ServicesController */
/* @var $model Services */

$this->breadcrumbs=array(
	'Главная'=>'/',
    'Услуги'=>array('admin'),
	$model->title . ' / Изменить',
);

$this->menu=array(
	array('label'=>'Все услуги', 'url'=>array('admin')),
	array('label'=>'Добавить новую услугу', 'url'=>array('create')),
);
?>

<h1>Обновить данные услуги "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>