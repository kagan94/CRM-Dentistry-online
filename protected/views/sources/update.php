<?php
/* @var $this SourcesController */
/* @var $model Sources */

$this->breadcrumbs=array(
	'Главная'=>'/',
    'Источники клиентов'=>array('admin'),
    $model->title . " / Изменить",
);

$this->menu=array(
	array('label'=>'Все источники клиентов', 'url'=>array('admin')),
	array('label'=>'Добавить новый источник', 'url'=>array('create')),
);
?>

<h1>Обновить данные записи "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>