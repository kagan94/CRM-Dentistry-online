<?php
/* @var $this DoctorsController */
/* @var $model Doctors */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Врачи'=>array('admin'),
	$model->fio,
);

$this->menu=array(
	array('label'=>'Все врачи', 'url'=>array('admin')),
	array(
        'label'=>'Добавить нового', 
        'url'=>array('create'),
        'visible'=> (Yii::app()->user->getName()=='admin') ? true : false, 
    ),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id), 'visible'=> (Yii::app()->user->getName()=='admin') ? true : false, ),
	array(
        'label'=>'Удалить', 
        'url'=>'#', 
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы подтверждаете удаление данного врача НАВСЕГДА?'
        ), 
        'visible'=> (Yii::app()->user->getName()=='admin') ? true : false,
    ),
);
?>

<h1>Просмотр данных врача "<?php echo $model->fio; ?>"</h1>

<?php 

    if ($model->trash == 1) {
        echo "<div class=\"red_msg\">! Данный врач находится в корзине !</div>";
    }

    $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'fio',
        'photo' => array(
            'name' => 'photo',
            'type'=>'html',
            'value' => isset($model->photo) ? $this->post_image($model->fio, $model->id.'.jpg', '150') : "" ,
            'visible'=> ($model->photo) != "" ? true : false ,
        ),
        'direction' => array(
            'name' => 'direction',
            'value' => isset($model->direction) ? $model->direction : "" ,
            'visible'=> ($model->direction) != "" ? true : false ,
        ),
		'phone',
        'email' => array(
            'name' => 'email',
            'value' => isset($model->email) ? $model->email : "" ,
            'visible'=> ($model->email) != "" ? true : false ,
        ),

        'affiliates' => array(
            'name' => 'affiliates',
            'type'=>'html',
            'value' => isset($model->affiliates) ? $model->affiliates : "" ,
            'visible'=> ($model->affiliates) != "" ? true : false ,
        ),
	),
));
?>
