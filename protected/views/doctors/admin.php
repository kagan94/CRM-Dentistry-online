<?php
/* @var $this DoctorsController */
/* @var $model Doctors */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Врачи'=>array('admin'),
	/* 'Manage', */
);

$this->menu=array(
	array('label'=>'Добавить нового врача', 'url'=>array('create')),
	array('label'=>'Корзина', 'url'=>array('trash')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#doctors-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Информация о врачах</h1>

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
	'id'=>'doctors-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		// 'photo',
		//'email',
        array(
            'name' => 'fio',
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
        'direction',
		'phone',
        'affiliates' => array(
            'name' => 'affiliates',
            'type'=>'html',
            'value' => '$data->getAffiliates($data->doctorAffiliates)',
        ),
		 array(
			'class'=>'CButtonColumn',
			'htmlOptions' => array('class' => 'btnCol'),
		    'template'    => (Yii::app()->user->getName()=='admin') ? '{view} &nbsp;{update}&nbsp; {delete}' : '{view}',
		    //'deleteConfirmation'=>'Вы уверены что хотите перенести доктора в корзину?',
		    'buttons'     => array(
                'view' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/view.png',
                ),
                'update' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/update.png',
                ),
		        'delete' => array(
		            'icon'  => 'trash',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/admin/delete.png',
		            'label' => 'Перенести в корзину',
		            'url'   => 'Yii::app()->controller->createUrl("movetotrash", array("id" => $data->id))',

		            'click' => 'js:function confirmationRenew() {
					 	if (confirm("Вы уверены что хотите перенести врача в корзину?")){
							var url = $(this).attr("href");

							$.ajax({
								url: url,
								type: "GET",
								dataType: "json",
								success: function (data) {
									$("#doctors-grid").yiiGridView("update");
								}
							});

						}
							return false;
		            }',
	            )
            ),
		),
	),
)); 
?>
