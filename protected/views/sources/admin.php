<?php
/* @var $this SourcesController */
/* @var $model Sources */

$this->breadcrumbs=array(
	'Главная'=>'/',
    'Источники клиентов',
);

$this->menu=array(
	array('label'=>'Добавить новый источник', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sources-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление полями "Источники клиентов"</h1>

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
	'id'=>'sources-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'title',
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
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
