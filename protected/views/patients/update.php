<?php
/* @var $this PatientsController */
/* @var $model Patients */

$this->breadcrumbs=array(
	'Главная'=>'/',
	'Пациенты'=>array('admin'),
	'Изменить (' . $model->fio . ') ',
);

$this->menu=array(
	array('label'=>'Все пациенты', 'url'=>array('admin')),
	array('label'=>'Добавить нового пациента', 'url'=>array('create')),
);

echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.yiigridview.js'); // для Grid'a
echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.ba-bbq.js');
echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.datetimepicker.js');
echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/jquery.qtip.min.js');
echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/lightbox.min.js'); // для стр. загрузки

$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.qtip.min.css');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/lightbox.css');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/jquery.datetimepicker.css');

?>

<h1>Обновить данные пациента "<?php echo $model->fio; ?>"</h1>

<?php $this->widget('zii.widgets.jui.CJuiTabs',array(
    'tabs'=>array(
        'Информация о пациенте'=>array('id'=>'tab-id1','content'=>$this->renderPartial(
            '_tab_1',
            array('model'=>$model),TRUE
        )),
        'История лечений'=>array('ajax'=>array('patients/AjaxGetPatientHistoryInfo','patient_id'=>$model->id)),
        '<span style=\'color:green;\'>Зубная карта</span>'=>array('id'=>'tab-id3','content'=>$this->renderPartial(
            '_tab_3',
            array('modelTeethMap'=>$modelTeethMap,'model'=>$model),TRUE
        )),
        'Прикрепить файлы'=>array('id'=>'tab-id4','content'=>$this->renderPartial(
            '_tab_4',
            array('all_files'=>$all_files, 'patient_id'=>$model->id),TRUE
        )),
        'Денежные операции'=>array('ajax'=>array('patients/AjaxPatientTransactions','patient_id'=>$model->id)),
    ),
    'options'=>array(
        'collapsible'=>true,
    ),
)); ?>

<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/ajax/save_patient_history.js'); ?>
<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . '/js/services_func.js'); ?>
