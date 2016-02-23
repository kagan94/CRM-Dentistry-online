<?php

class DoctorsController extends Controller
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
			/*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			*/
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete','create','update','index', 'trash', 'MoveToTrash', 'restore'),
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
		$model = $this->loadModel($id);

        $aff_names = "";

        if($model->affiliates != 0){
           foreach ($model->affiliates as $aff_id) {
               $aff_info = Affiliate::model()->findByPk($aff_id);

               $aff_names .= $aff_info->name . " - " . $aff_info->city . " (" . $aff_info->adress . ")<br>"; 
           }

           $model->affiliates = $aff_names;
        }

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Doctors;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Doctors']))
		{
			$model->attributes=$_POST['Doctors'];
			if (isset($model->doctor_image)) $model->doctor_image=CUploadedFile::getInstance($model, 'doctor_image');
				
			if($model->save() && $model->validate()){
            	//Если отмечен чекбокс «удалить файл»            
				if($model->del_image) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/doctors/'.$model->id.'.jpg')) {
						unlink('./images/doctors/'.$model->id.'.jpg');
					}
					$model->photo = '';
				}

				//Если поле загрузки файла не было пустым           
				if ($model->doctor_image){
					$model->doctor_image->saveAs('images/doctors/'.$model->id.'.jpg');
					$model->photo = $model->id.'.jpg';
				}

				$model->save();

				if(is_array($model->affiliates)){
					foreach ($model->affiliates as $affiliate) {
						$addAffToDoctor = new DoctorAffiliate();
						$addAffToDoctor->doctor_id = $model->id;
						$addAffToDoctor->affiliate_id = $affiliate;
						$addAffToDoctor->save();
					}
				}

				$this->redirect(array('view','id'=>$model->id));
			}
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

		if(isset($_POST['Doctors']))
		{
			$model->attributes=$_POST['Doctors'];


			// $model->

   			// $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
			// $model->photo
	
			// $model->photo = $model->id.'.jpg';

            // $tempSave=CUploadedFile::getInstance($model, 'filename');
            // $model->photo=CUploadedFile::getInstance($model,'doctor_image');

            // if ($model->photo!=NULL)
            //   $model->photo->saveAs('./images/doctors/'.$model->id.'-'.$model->photo);
           
           if (isset($model->doctor_image)) $model->doctor_image=CUploadedFile::getInstance($model, 'doctor_image');
				
			if($model->validate()) {
            	//Если отмечен чекбокс «удалить файл»            
				if($model->del_image) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/doctors/'.$model->id.'.jpg')) {
						unlink('./images/doctors/'.$model->id.'.jpg');
					}
					$model->photo = '';
				}

				//Если поле загрузки файла не было пустым           
				if ($model->doctor_image){
					$model->doctor_image->saveAs('images/doctors/'.$model->id.'.jpg');
					$model->photo = $model->id.'.jpg';
				}
			}

			if($model->save()){
				
				DoctorAffiliate::model()->deleteAllByAttributes(array('doctor_id'=>$model->id));

				if(is_array($model->affiliates)){
					foreach ($model->affiliates as $affiliate) {
						$addAffToDoctor = new DoctorAffiliate();
						$addAffToDoctor->doctor_id = $model->id;
						$addAffToDoctor->affiliate_id = $affiliate;
						$addAffToDoctor->save();
					}
				}

				$this->redirect(array('view','id'=>$model->id));
			}
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
		DoctorAffiliate::model()->deleteAllByAttributes(array('doctor_id'=>$id));

		$this->loadModel($id)->delete();

		// Удаляем фото доктора
		if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/doctors/'.$id.'.jpg')) {
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/doctors/'.$id.'.jpg');
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		// $dataProvider=new CActiveDataProvider('Doctors');
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
		$model=new Doctors('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Doctors']))
			$model->attributes=$_GET['Doctors'];

		if( !(Yii::app()->user->getName()=='admin') )
			$this->layout='//layouts/column1';

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	// show Trash 
	public function actionTrash()
	{
		$model=new Doctors('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Doctors']))
			$model->attributes=$_GET['Doctors'];

		$this->render('trash',array(
			'model'=>$model,
		));
	}


	public function actionRestore($id){

		$doctor = Doctors::model()->findByPk($id);
		$doctor->trash = 0;

		if($doctor->save()){
			echo CJSON::encode(array(
               'status'=>'success',
	        ));
		} else {
			echo CJSON::encode(array(
               'status'=>'fail',
	        ));
		}

	}


	public function actionMoveToTrash($id)
	{
		$doctor = Doctors::model()->findByPk($id);
		$doctor->trash = 1;

		if($doctor->save()){
			echo CJSON::encode(array(
               'status'=>'success',
	        ));
		} else {
			echo CJSON::encode(array(
               'status'=>'fail',
	        ));
		}
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Doctors the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Doctors::model()->findByPk($id);

		$allAffsToDoctor = DoctorAffiliate::model()->findAllByAttributes(array('doctor_id'=>$id));

		foreach ($allAffsToDoctor as $affiliate) {
			$model->affiliates[] = $affiliate->affiliate_id;
		}


		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Doctors $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='doctors-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function post_image($title, $image, $width='150', $class='post_img')
	{
	  if(isset($image) && file_exists($_SERVER['DOCUMENT_ROOT'].
	    Yii::app()->urlManager->baseUrl.
	    '/images/doctors/'.$image))
	      return CHtml::image(Yii::app()->getBaseUrl(true).'/images/doctors/'.$image, $title,
	        array(
	          'width'=>$width,
	          'class'=>$class,
	        )
	      );
	  else
	    return CHtml::image(Yii::app()->getBaseUrl(true).'/images/doctors/noimage.png','Нет картинки',
	      array(
	        'width'=>$width,
	        'class'=>$class
	      )
	    );
	 }


	 // public function getAffiliates($id){

  //       $criteria=new CDbCriteria;
  //       //$criteria->select='order_service_id, service_id, quantity';
  //       $criteria->condition = 'doctor_id = "'. $id .'"';

		// $affs = DoctorAffiliate::model()->with('affiliates')->findAll($criteria);
		// $affiliates = "";

		// foreach ($affs as $aff) {
		// 	$affiliates .= $aff->affiliate_id . "<br>";
		// }
		// //	array('select' => 'id, concat("\"", name, "\" - ", city, " ", " (" , adress, ")") as name'));

		// return $affiliates;

	 // }
}
