<?php
/* @var $this DiagnosesController */
/* @var $model Diagnoses */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Диагнозы'=>array('admin'),
	/* 'Manage', */
);

$this->menu=array(
	array('label'=>'Добавить диагноз', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#diagnoses-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление диагнозами</h1>

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
	'id'=>'diagnoses-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'id',
            'htmlOptions'=>array('style'=>'width:40px;'),
        ),
        array(
            'name' => 'title',
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} &nbsp;{delete}',
			'buttons' => array(
				'update' => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/update.png',
				),
				'delete' => array(
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/delete.png',
				),
			)
		),
	),
)); ?>
