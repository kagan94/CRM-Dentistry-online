<?php
/* @var $this SourcesController */
/* @var $model Sources */

$this->breadcrumbs=array(
	'Главная'=>'/',
    'Источники клиентов'=>array('admin'),
	'Добавить новый',
);

$this->menu=array(
	array('label'=>'Все источники', 'url'=>array('admin')),
);
?>

<h1>Добавить новый источник клиентов</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>