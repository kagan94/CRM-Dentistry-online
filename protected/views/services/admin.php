<?php
/* @var $this ServicesController */
/* @var $model Services */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Услуги'=>array('admin'),
);


$this->menu=array(
	array(
		'label'=>'Добавить новую услугу', 'url'=>array('create'),
		'visible'=> (Yii::app()->user->getName()=='admin') ? true : false,
	),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#services-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});

$('.filters').hide();

");
?>

<h1>Управление услугами</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'services-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableSorting'=>false,
	'columns'=>array(
        array(
            'name'  => 'code',
            'type'  => 'html',
            'filter'=> false,
            'value' => '($data->parent_id != 0) ? "<center><b>".(float)($data->code)."</b><c/center>" : ""',
            'htmlOptions'=>array('width'=>'70px', 'style'=>'text-align:left;'),
        ),
        array(
            'name' => 'title',
            'type'=>'html',
            'filter'=> false,
            'value'=>'($data->parent_id == 0) ? "<center><b><u>$data->title</u></b></center>" : "$data->title"',
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
        array(
            'name' => 'price',
            'filter'=> false,
            'value'=>'($data->price != 0 && $data->parent_id != 0 ) ? "$data->price грн." : ""',
            'htmlOptions'=>array('width'=>'150px'),
        ),
		array(
			'class'=>'CButtonColumn',
			'template'=> "{update} &nbsp; {delete}",
			'visible'=> (Yii::app()->user->getName()=='admin') ? true : false,
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
