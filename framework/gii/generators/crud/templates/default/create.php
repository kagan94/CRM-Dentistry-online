<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('admin'),
	'Добавить новый',
);\n";
?>

$this->menu=array(
	array('label'=>'Все <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
);
?>

<h1>Добавить новый <?php echo $this->modelClass; ?></h1>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
