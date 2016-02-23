<?php
/* @var $this DiscountsController */
/* @var $model Discounts */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Скидки'=>array('admin'),
	/* 'Manage', */
);

$this->menu=array(
	array('label'=>'Добавить новую скидку', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#discounts-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление скидками</h1>

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
	'id'=>'discounts-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'discount_id',
		// 'type',
		'title',
        array(
            'name'  => 'size',
            'value' => '$data->type == 1 ? "$data->size грн." : "$data->size %" ',
            'filter'=> CHtml::dropDownList('Discounts[type]', $model->type, 
              array('1' => 'В грн', '2' => 'В %'),
              array('empty' => 'Выберите тип скидки')
             ),
           // CHTML::dropDownList($model,'type',  array('1' => 'В грн', '2' => 'В %'), array('prompt'=> 'Выберите тип скидки', 'style'=>'width:170px') ),
        ),
		//'date_added',
		//'date_modifidied',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} &nbsp;{delete}',
			'visible'=> (Yii::app()->user->getName()=='admin') ? true : false,
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
