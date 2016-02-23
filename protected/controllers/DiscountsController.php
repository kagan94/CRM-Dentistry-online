<?php

class DiscountsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete','update', 'create'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		// $this->render('view',array(
		// 	'model'=>$this->loadModel($id),
		// ));
		$this->redirect(array('admin'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Discounts;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Discounts']))
		{
			$_POST['Discounts']['date_added'] = date("Y-m-d");

			$model->attributes=$_POST['Discounts'];
			if($model->save())
				$this->redirect(array('admin'));
				// $this->redirect(array('view','id'=>$model->discount_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Discounts']))
		{
			$_POST['Discounts']['date_modifidied'] = date("Y-m-d");

			$model->attributes=$_POST['Discounts'];
			if($model->save())
				$this->redirect(array('admin'));
				// $this->redirect(array('view','id'=>$model->discount_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		// $dataProvider=new CActiveDataProvider('Discounts');
		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,
		// ));
		$this->redirect(array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Discounts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Discounts']))
			$model->attributes=$_GET['Discounts'];

		if( !(Yii::app()->user->getName()=='admin') )
			$this->layout='//layouts/column1';
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Discounts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Discounts::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Discounts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='discounts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
