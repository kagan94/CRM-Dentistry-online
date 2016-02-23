<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Ошибка доступа';
$this->breadcrumbs=array(
	'Ошибка доступа',
);
?>

<h2>Ошибка <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>