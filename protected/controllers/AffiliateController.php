<?php

class AffiliateController extends Controller
{
    public $affiliate_title = "Филиалы";
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
			/*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			*/
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','view','create','update','index','field'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Affiliate;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Affiliate']))
		{
			$model->attributes=$_POST['Affiliate'];
			if($model->save()){

				foreach ($_POST['WorkingPlaces']['title'] as $key => $val) {
					if(trim($val) != ''):
						$place = new WorkingPlaces;
						$place->affiliate_id = $model->id;
						$place->title = trim($val);
						$place->save();
					endif;
				}

				$this->redirect(array('admin'));
			}
				//$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Affiliate']))
		{
			$model->attributes=$_POST['Affiliate'];

			foreach ($_POST['WorkingPlaces']['title'] as $key => $val) {
				if(isset($_POST['WorkingPlaces']['id'][$key]) && $_POST['WorkingPlaces']['id'][$key] != ''){
					$working_place = WorkingPlaces::model()->findByPk((int)$_POST['WorkingPlaces']['id'][$key]);
					
					if(trim($val) == ''):
						$working_place->delete();
					else:
						$working_place->title = trim($val);
						$working_place->save();
					endif;

				} else {
					if(trim($val) != ''):
						$place = new WorkingPlaces;
						$place->affiliate_id = $id;
						$place->title = trim($val);
						$place->save();
					endif;
				}
			}

			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionDelete($id)
	{
	 	WorkingPlaces::model()->deleteAll('affiliate_id ='.$id);
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	public function actionIndex()
	{
		$this->redirect(array('admin'));
	}


	public function actionAdmin()
	{
		$model=new Affiliate('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Affiliate']))
			$model->attributes=$_GET['Affiliate'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Affiliate the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Affiliate::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Affiliate $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='affiliate-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function actionField($index = false)
    {
        $model = new WorkingPlaces();
        $this->renderPartial('_add_working_place', array(
            'model' => $model,
            'index' => $index,
        ));
    }

}
