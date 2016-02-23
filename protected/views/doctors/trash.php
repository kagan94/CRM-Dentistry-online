<?php
/* @var $this DoctorsController */
/* @var $model Doctors */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Все врачи'=>array('admin'),
	'Корзина', 
);

$this->menu=array(
	array('label'=>'Все врачи', 'url'=>array('admin')),
);
?>

<h1>Корзина (Врачи которые были удалены из основного списка)</h1>

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'doctors-grid',
	'dataProvider'=>$model->search(array('trash'=>'1')),
	'filter'=>$model,
	'columns'=>array(
        array(
            'name' => 'fio',
            'htmlOptions'=>array('style'=>'text-align:left;'),
        ),
        'direction',
		'phone',
		array(
			'class'=>'CButtonColumn',
			'template'    => '{view} &nbsp;{renew}&nbsp; {delete} ',
		    'deleteConfirmation'=>'Вы уверены что хотите УДАЛИТЬ ДОКТОРА НАВСЕГДА?',
		    'buttons'     => array(
                'view' => array(
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/view.png',
                ),
		        'renew' => array(
		            'url'   => 'Yii::app()->createUrl("/doctors/restore", array("id"=>$data->id))',
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/renew.png',
                	'options'=>array(
           				'title'=>'Восстановить доктора из корзины'
					),

		            'click' => 'js:function confirmationRenew() {

					 	if (confirm("Вы уверены что хотите восстановить доктора?")){
							var url = $(this).attr("href");

							$.ajax({
								url: url,
								type: "GET",
								dataType: "json",
								success: function (data) {
									$("#doctors-grid").yiiGridView("update");
								}
							});

							return false;
						}
		            }',
		        ),
		        'delete' => array(
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/admin/delete.png',
	        	),
			),
		),
	),
)); 
?>
