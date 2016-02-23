<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Форма входа в CRM-Online</title>
    <meta http-equiv="content-type" content="text/html; charset=windows-1251">
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/themes/blackboot/css/login.css" />
</head>
<body>


<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); 
?>
    <h1>Вход</h1>
    <fieldset id="inputs">
        <div class="errorMessage">
            <?php
                if(isset($model->errors['verifyCode'][0])){
                    echo $model->errors['verifyCode'][0];
                } else {
                    foreach ($model->errors as $error) {
                        echo $error[0] . '<BR>';
                    }
                }
            ?>
        </div>

        <div class="username-block">
            <?php echo $form->textField($model,'username', array('size'=>60,'maxlength'=>255,'placeholder'=>'Логин','id'=>'username')); ?>
        </div>

        <div class="password-block">
            <?php echo $form->passwordField($model,'password', array('size'=>60,'maxlength'=>255,'placeholder'=>'Пароль','id'=>'password')); ?> 
        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model,'rememberMe', array('style'=>'width: 20px;height: 16px;')); ?>
            <?php echo $form->label($model,'rememberMe', array('class'=>"label-remember", 'style'=>'float: right; font-size: 15px;')); ?><span class="show-pass icon-eye" title="show characters"></span>
        </div>
    </fieldset>

    <div class="captcha">
        <div class="text">
            Введите полученный результат с картинки:
            <br>
            <?php echo $form->textField($model,'verifyCode'); ?>
        </div>
        <div class="verifyCode">
            <?php $this->widget('CCaptcha'); ?>
        </div>  
        <?php // echo $form->error($model,'verifyCode'); ?>
    </div>
    
    <div id="actions">
        <?php echo CHtml::submitButton('Войти', array('class'=>"button", 'id'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>

</body>
</html>
