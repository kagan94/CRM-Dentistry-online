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
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('admin'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Изменить',
);\n";
?>

$this->menu=array(
	array('label'=>'Все <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
	array('label'=>'Добавить новый', 'url'=>array('create')),
	array('label'=>'Просмотр <?php echo $this->modelClass; ?>', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
);
?>

<h1>Обновить данные филиала <?php echo $this->modelClass." \"<?php echo \$model->{$this->tableSchema->primaryKey}; ?>\""; ?></h1>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>