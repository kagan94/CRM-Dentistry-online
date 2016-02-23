<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пользователи'=>array('admin'),
);

$this->menu=array(
	array('label'=>'Создать нового', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление пользователями</h1>

<p>
Также при поиске можно использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
или <b>=</b>).
</p>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'login',
		'name',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} &nbsp; {delete}',
		    'buttons'     => array(
                'update' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/update.png',
                ),
		        'delete' => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/delete.png',
				),
			),
		),
	),
)); ?>
