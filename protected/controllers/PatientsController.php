<?php

class PatientsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	private $path_to_file='upload/patients_files/';
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
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array('index','view'),
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('attach_files','files','deletefile','admin','view','create','update','UpdatePatient',
					'CreatePatient', 'SaveTeethMap','GetPatientTeethStatus', 'AjaxGetPatientHistoryInfo',
					'Print','AjaxPatientTransactions'
				),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionView($id)
	{
		 $criteria=new CDbCriteria(array(
	        'condition'=>'patient_id='.$id,
	        'order'=>'datetime_visit DESC',
	    ));

	    $visits=new CActiveDataProvider('PatientsHistory', array(
	        'pagination'=>array(
	            'pageSize'=>100,
	        ),
	        'criteria'=>$criteria,
	    ));
	 
		$this->renderPartial('view',array(
			'visitsProvider'=>$visits,
			'model'=>$this->loadModel($id),
		));
	}


	public function actionPrint($id)
	{
		// Формируем PDF карточку

		$model=$this->loadModel($id);

		$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->setDefaultFont('freesans');
		$html2pdf->writeHTML( $this->renderPartial('print', array('model'=>$model), true, false) );
		$html2pdf->Output();
	}


	public function actionCreate()
	{
		$model=new Patients;
		$modelPatientHistory=new PatientsHistory;

		Yii::app()->user->setState('patient_id', null);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Patients']))
		{
			$_POST['Patients']['date_added'] = date("Y-m-d");

			$model->attributes=$_POST['Patients'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $modelTeethMap = new ToothHistory();

		$this->render('create',array(
			'model'=>$model,
            'modelTeethMap'=>$modelTeethMap,
            'modelPatientHistory'=>$modelPatientHistory,
            'all_files'=>array(),
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

		Yii::app()->user->setState('patient_id', $id);

        // Загружаем историю лечений пациента
        $modelPatientHistory = new PatientsHistory('search');
        $modelPatientHistory->patient_id=$model->id;
       // $modelPatientHistory->unsetAttributes();  // clear any default values
        if(isset($_GET['PatientsHistory']))
            $modelPatientHistory->attributes=$_GET['PatientsHistory'];

		if(isset($_POST['Patients']))
		{
			$_POST['Patients']['date_modifidied'] = date("Y-m-d");

			$model->attributes=$_POST['Patients'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $modelTeethMap = new ToothHistory();
        $modelTeethMap->patient_id = $model->id;

        // Выборка по файлам
		$criteria=new CDbCriteria(array(
	        'condition'=>'patient_id='.$id,
	    ));
     	$criteria->order = 'uploaded_time DESC';

		$all_files = PatientsFiles::model()->FindAll($criteria);

		$this->render('update',array(
			'model'=>$model,
            'modelPatientHistory'=>$modelPatientHistory,
            'modelTeethMap'=>$modelTeethMap,
            'all_files'=>$all_files,
		));
	}


	public function actionDelete($id)
	{
				$criteria = new CDbCriteria;
		$criteria->condition = "operation = 1 AND patient_id LIKE :patient_id";
		$criteria->params = array(":patient_id"=>$id);
		$count_patient_transactions = PatientBalance::model()->findAll($criteria);

		if(count($count_patient_transactions) > 0 ){
     		throw new CHttpException(400, "Вы не можете удалить данного пациента, так как пациент уже вносил оплату. \nТолько после удаления всех транзакций, вы сможете удалить пациента.");
		} else {
			$this->loadModel($id)->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		// $dataProvider=new CActiveDataProvider('Patients');
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
		$model=new Patients('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Patients']))
			$model->attributes=$_GET['Patients'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Patients the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Patients::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		
		if($model->date_birthday == "0000-00-00") 
			$model->date_birthday = "";
		
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Patients $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='patients-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionUpdatePatient($id=null)
	{
        if(isset($_POST['Patients']) && isset($id) && $id != 0) {
            // Если данные пациента изменены (стр. изменения)
            $model = $this->loadModel((int)$id);

        } else if(isset($_POST['Patients']['id']) && !isset($id)) {
            // Если данные пациента будут сохранены (стр.создания)
            $model = $this->loadModel((int)$_POST['Patients']['id']);
        }

        if(isset($_POST['Patients'])){
            $_POST['Patients']['date_modifidied'] = date("Y-m-d");
            $model->attributes=$_POST['Patients'];

            $valid=$model->validate();
            if($valid && $model->save()){

                //do anything here
                echo CJSON::encode(array(
                    'status'=>'success'
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if($error!='[]')
                    echo $error;
                Yii::app()->end();
            }
        }
	}

	public function actionCreatePatient()
	{
	    $model = new Patients();   
	    Yii::app()->user->setState('patient_id', null);

	    if(isset($_POST['Patients'])) {
			$_POST['Patients']['date_added'] = date("Y-m-d");

	        $model->attributes=$_POST['Patients'];

	        $valid=$model->validate();            
	        if($valid && $model->save()){

				Yii::app()->user->setState('patient_id', $model->id); // Заносим patient_id в сессию (для файлов)
	        
	           //do anything here
	             echo CJSON::encode(array(
	                  'status'=>'success',
	                  'patient_id'=>$model->id,
	             ));
	            Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if($error!='[]')
                    echo $error;
                Yii::app()->end();
            }
	    }
	}

    public function actionSaveTeethMap()
    {
        $errors = 0 ;
        $array_added = array();
        $array_deleted = array();

        if(isset($_POST['ToothHistory']) && $_POST['ToothHistory']['patient_id'] != "" && $_POST['ToothHistory']['tooth_number'] != "") {

            foreach($_POST['ToothHistory']['status_id'] as $key=>$val){

                if((int)$_POST['ToothHistory']['tooth_history_id'][$key] == 0 && (int)$val == 1){
                    $newToothHistory=new ToothHistory();
                    $newToothHistory->patient_id=$_POST['ToothHistory']['patient_id'];
                    $newToothHistory->tooth_number=$_POST['ToothHistory']['tooth_number'];
                    $newToothHistory->status_id=$key;

                    if($newToothHistory->save()){
                        $array_added[$key] = $newToothHistory->tooth_history_id;
                    } else {
                        $errors = 1;
                    }
                } else if((int)$_POST['ToothHistory']['tooth_history_id'][$key] != 0 && (int)$val == 0){
                    $deleteToothHistory=ToothHistory::model()->findByPk((int)$_POST['ToothHistory']['tooth_history_id'][$key]);
                    $deleteToothHistory->delete();

                    $array_deleted[] = $key;
                }
            }

            if($errors == 0){
                echo CJSON::encode(array(
                    'status'=>'success',
                    'arrAdded'=> $array_added,
                    'arrDeleted'=> $array_deleted,
                    $_POST
                ));
            }
        }
    }

    public function actionGetPatientTeethStatus()
    {
        if(isset($_POST['patient_id']) && isset($_POST['tooth_number'])) {

            $criteria=new CDbCriteria;
            $criteria->select='tooth_history_id, tooth_number, status_id';
            $criteria->condition = 'patient_id = "'.  (int)$_POST['patient_id'] .'" AND tooth_number = "'. (int)$_POST['tooth_number'] .'"';
            $ToothHistories = ToothHistory::model()->findAll($criteria);

            $array = array();
            $toothTips = array();

            if($ToothHistories){
                foreach($ToothHistories as $toothHistory){
                    $array[$toothHistory['status_id']] = $toothHistory['tooth_history_id'];

                    // Массив для подсказок
                    if(array_key_exists($toothHistory['tooth_number'], $toothTips)){
                        $toothTips[$toothHistory['tooth_number']] .= Patients::getStatus($toothHistory['status_id']);
                    } else {
                        $toothTips[$toothHistory['tooth_number']] = Patients::getStatus($toothHistory['status_id']);
                    }
                }
            } else {
                $toothTips=0;
                $array=0;
            }

            echo CJSON::encode(array(
                'status' => 'success',
                'patient_id' => $_POST['patient_id'],
                'tooth_number' => $toothTips,
                'tooth_statuses' => $array,
            ));
        }
    }

    public function actionAjaxGetPatientHistoryInfo()
    {
        $model=new PatientsHistory('search');
        $model->unsetAttributes();
        if(isset($_GET['PatientsHistory']))
            $model->attributes=$_GET['PatientsHistory'];

        $model->patient_id = $_GET['patient_id'];

        $this->renderPartial('_tab_2',array('model'=>$model),false,true);
    }

    public function actionAjaxPatientTransactions() 
    {
        $patient_id = (int)$_GET['patient_id'];

		$criteria=new CDbCriteria(array(
		    'condition'=>'patient_id='.$patient_id,
		    'order'=>'date DESC',
		));

		$transactions = PatientBalance::model()->findAll($criteria);

        $this->renderPartial('_tab_5',array(
        	'patient_id'=>$patient_id,
        	'transactions'=>$transactions,
        	),false,true);
    }

    public function getBackspaces($max_number=0)
    {
        $backs = "";

        for($i=0; $i<$max_number; $i++){
        	$backs .= "&nbsp;";
        }

        return $backs;
    }


	public function actionAttach_files()
	{
    	Yii::import("ext.EAjaxUpload.qqFileUploader");
 
        // $patient_id = (int)Yii::app()->request->getParam('patient_id');

		$patient_id = (int) (Yii::app()->user->getState('patient_id'));

		$folder = $this->path_to_file.$patient_id.'/'; // Folder for uploaded files

			// Does the folder exist? If no, create it.
		if(!is_dir($folder)){
		    mkdir($folder, 0755);
		}

        $allowedExtensions = array("jpg","jpeg","gif","png");
        $sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
 
 		if($result['success'] == true){
 			$newFile = new PatientsFiles;
 			$newFile->patient_id = $patient_id;
 			$newFile->file_name = $result['filename'];
 			$newFile->uploaded_time = date("Y-m-d H:i:s");
 			$newFile->save();

 			$result['file_id'] = $newFile->id;
 			$result['uploaded_time'] = $newFile->uploaded_time;
 			$result['patient_id'] = $patient_id;
 		}

        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);

        echo $return;
	}

	public function actionDeletefile() {

		$file_id = (int)$_POST['file_id'];
		$file = PatientsFiles::model()->FindByPk($file_id);
		$full_path = $this->path_to_file.$file->patient_id.'/';

		if(is_dir($full_path)) {
		 
		 if(is_file($full_path.$file->file_name)){
			unlink($full_path.$file->file_name);
		 }

		 if(count(glob($full_path.'/*')) == 0){
		    rmdir($full_path); // Удаляем папку, если пустая 
		 }
		}

		if($file->delete()){
            echo CJSON::encode(array(
                'status'=>'success',
            ));
		} else {            
			echo CJSON::encode(array(
                'status'=>'error',
            ));
		}
	}

    // public function actionPatientBalance() {
    //     // $model=new PatientsHistory('search');
    //     // $model->unsetAttributes();
    //     // if(isset($_GET['PatientsHistory']))
    //     //     $model->attributes=$_GET['PatientsHistory'];

    //     // $model->patient_id = $_GET['patient_id'];

    //     $this->renderPartial('_tab_4', array('model'=>$model),false,true);
    // }
}
