<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пользователи'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Все пользователи', 'url'=>array('admin')),
	array('label'=>'Создать нового', 'url'=>array('create')),
	array('label'=>'Обновить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены что хотите удалить пользователя "'.$model->name.'" ?')),
);
?>

<h1>Просмотр пользователя "<?php echo $model->login; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'login',
		'password',
		'name',
	),
)); ?>
