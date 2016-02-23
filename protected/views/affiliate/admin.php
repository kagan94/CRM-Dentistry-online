<?php
/* @var $this AffiliateController */
/* @var $model Affiliate */

$this->breadcrumbs=array(
	'Главная'=>'/',
    $this->affiliate_title =>array('admin'),
);

$this->menu=array(
    array('label'=>'Добавить новый', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#affiliate-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление филиалами</h1>

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
	'id'=>'affiliate-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'city',
		'adress',

		array(
			'class' => 'CButtonColumn',
			'htmlOptions' => array('style' => 'white-space: nowrap'),
			'afterDelete' => 'function(link,success,data) { if (success && data) alert(data); }',
			'template'=>'{update}{delete}',

			'buttons' => array(
				'update' => array(
					'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'class' => 'update', 'style' => 'margin:0px 8px;'),
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/update.png',
				),
				'delete' => array(
					'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'class' => 'delete' ),
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/delete.png',
				),
			)
		),
	),
)); ?>
