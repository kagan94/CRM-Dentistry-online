<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jqueryslidemenu.css" />

    <!--[if lte IE 7]>
    <style type="text/css">
        html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
    </style>
    <![endif]-->

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqueryslidemenu.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="myslidemenu" class="jqueryslidemenu">

<?php
        $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>array('/site/index'), 'visible' => !Yii::app()->user->isGuest, ),
                array('label'=>'Управление', 'visible' => !Yii::app()->user->isGuest, 'items'=>array(
                    array('label'=>'Пользователи', 'url'=>array('/user/admin'), ),
                    array('label'=>'Источники клиентов', 'url'=>array('/sources/admin')),
                    array('label'=>'Филиалы', 'url'=>array('/affiliate/admin')),
                    array('label'=>'Доктора', 'url'=>array('/doctors/admin')),
                    array('label'=>'Скидки', 'url'=>array('/discounts/admin')),
                    array('label'=>'Услуги', 'url'=>array('/services/admin')),
                )),
                array('label'=>'Пациенты', 'url'=>array('/patients/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label'=>'История лечений', 'url'=>array('/patientsHistory/admin'),'visible' => !Yii::app()->user->isGuest),
                array('label'=>'Диагнозы', 'url'=>array('/diagnoses/admin'), 'visible' => !Yii::app()->user->isGuest ),
				array('label'=>'Вход', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>

	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; 2014-<?php echo date('Y'); ?> by Leo Dashko.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
