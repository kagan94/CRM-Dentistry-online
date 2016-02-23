<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пользователи'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Обновить',
);

$this->menu=array(
	array('label'=>'Все пользователи', 'url'=>array('admin')),
	array('label'=>'Создать нового', 'url'=>array('create')),
	array('label'=>'Просмотр пользователя', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Изменить данные пользователя "<?php echo $model->name; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>