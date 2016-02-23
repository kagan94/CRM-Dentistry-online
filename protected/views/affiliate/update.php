<?php
/* @var $this AffiliateController */
/* @var $model Affiliate */

$this->breadcrumbs=array(
	'Главная'=>'/',
	$this->affiliate_title =>array('admin'),
	//$model->name=>array('view','id'=>$model->id),
	$model->name . " / Изменить",
);

$this->menu=array(
    array('label'=>'Все филиалы', 'url'=>array('admin')),
    array('label'=>'Добавить новый филиал', 'url'=>array('create')),
   // array('label'=>'Просмотр филиала', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Обновить данные филиала "<?php echo $model->name; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>