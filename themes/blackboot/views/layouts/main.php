<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta name="language" content="ru" />
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- Le styles -->

<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
<link rel="icon" href="/images/favicon.ico" type="image/x-icon">	
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
<!-- Le fav and touch icons -->
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/"><?php echo Yii::app()->name ?></a>
			<div class="nav-collapse">
				<?php $this->widget('ext.cssmenu.CssMenu',array(
					//'htmlOptions' => array( 'class' => 'nav' ),
					//'activeCssClass'	=> 'active',
					'items'=>array(
						array('label'=>'Главная', 'url'=> '/./', 'visible'=>!Yii::app()->user->isGuest), 
						array('label'=>'Настройки', 'visible' => !Yii::app()->user->isGuest, 'items'=>array(
							array('label'=>'Пользователи', 'url'=>array('/user/admin'), 'visible' => Yii::app()->user->getName()=='admin'),
							array('label'=>'Источники клиентов', 'url'=>array('/sources/admin'), 'visible' => Yii::app()->user->getName()=='admin'),
							array('label'=>'Филиалы', 'url'=>array('/affiliate/admin'),'visible' => Yii::app()->user->getName()=='admin'),
							array('label'=>'Врачи', 'url'=>array('/doctors/admin')),
							array('label'=>'Скидки', 'url'=>array('/discounts/admin')),
							array('label'=>'Услуги', 'url'=>array('/services/admin')),
							// array('label'=>'Диагнозы', 'url'=>array('/diagnoses/admin'), 'visible' => !Yii::app()->user->isGuest ),
                            array('label'=>'Статистика', 'url'=>array('/stats'),'visible' => Yii::app()->user->getName()=='admin'),
                        )),
                        array('label'=>'Пациенты', 'url'=>array('/patients/admin'), 'visible' => !Yii::app()->user->isGuest),
                        array('label'=>'Визиты пациентов', 'url'=>array('/patient/history/admin'),'visible' => !Yii::app()->user->isGuest),
                        array('label'=>'Расписание', 'url'=>array('/schedule'), 'visible' => !Yii::app()->user->isGuest),
						//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						//array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Вход', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); ?>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>
	
<div class="cont">
	<div class="container-fluid">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			'homeLink'=>false,
			'tagName'=>'ul',
			'separator'=>'',
			'activeLinkTemplate'=>'<li><a href="{url}">{label}</a> <span class="divider">/</span></li>',
			'inactiveLinkTemplate'=>'<li><span>{label}</span></li>',
			'htmlOptions'=>array ('class'=>'breadcrumb')
		)); ?>
	<!-- breadcrumbs -->
	<?php endif?>
	
	<?php echo $content ?>
	
	</div><!--/.fluid-container-->
</div>
	
	
<div class="footer">
  <div class="container">
	<div class="row">
		<div id="footer-terms" class="col-md-6">
			© 2014-<?=date('Y')?> | By Leonid Dashko
		</div> <!-- /.span6 -->
	 </div> <!-- /row -->
  </div> <!-- /container -->
</div>

<script>
	$('.btn-navbar').on('click', function(){
	 var div_menu = $('.nav-collapse');
	 div_menu.is(":visible")
	 if(div_menu.is(":visible")){
	   $('.nav-collapse').hide(500);  
	 } else {
	   $('.nav-collapse').show(500);
	 }
	 
	});
</script>

</body>
</html>
