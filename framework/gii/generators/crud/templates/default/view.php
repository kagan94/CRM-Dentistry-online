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
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
	array('label'=>'Все филиалы <?php echo $this->modelClass; ?>', 'url'=>array('admin')),
	array('label'=>'Добавить новый', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Удалить <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Вы подтверждаете удаление данного элемента?')),
);
?>

<h1>Просмотр филиала <?php echo $this->modelClass." \"<?php echo \$model->{$this->tableSchema->primaryKey}; ?>\""; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>
