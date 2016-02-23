<?php
/* @var $this AffiliateController */
/* @var $model Affiliate */

$this->breadcrumbs=array(
	'Главная'=>'/',
    $this->affiliate_title =>array('admin'),
	'Добавить новый',
);

$this->menu=array(
    array('label'=>'Все филиалы', 'url'=>array('admin')),
);
?>

<h1>Добавить новый филиал</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>