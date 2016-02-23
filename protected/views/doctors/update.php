<?php
/* @var $this DoctorsController */
/* @var $model Doctors */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Все врачи'=>array('admin'),
	$model->fio=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Все врачи', 'url'=>array('admin')),
	array('label'=>'Добавить нового врача', 'url'=>array('create')),
	array('label'=>'Данные этого врача', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Обновить данные врача "<?php echo $model->fio; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>