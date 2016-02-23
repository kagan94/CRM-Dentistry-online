<?php

class TransactionsController extends Controller
{
	public $layout='//layouts/column2';


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}


	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('add','getinfo', 'update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionAdd()
	{
		$patient_id 		= (int)$_POST['patient_id'];
		$patient_history_id = (int)$_POST['patient_history_id'];
		$date_visit 		= (string)$_POST['date'];
		$sum 				= (float)$_POST['sum'];
		$comment 			= (string)$_POST['comment'];
		$patient_history 	= NULL;
		$errors 			= array();

 		if($patient_history_id==0)
 			$errors[] = 'ID Визита пациента не может быть пустым.';
 		else
 			$patient_history = PatientsHistory::model()->findByPk($patient_history_id);

 		if($patient_history == NULL)
 			$errors[] = 'Визит пациента не найден.';

 		if($date_visit == "" || mb_strlen($date_visit) > 10)
 			$errors[] = 'Заполните дату в верном формате.';
 		
 		if(empty($errors)){
 			$transaction = new PatientBalance;
 			$_POST['operation'] = 1;
			$transaction->attributes=$_POST;
			$transaction->save();

	        echo CJSON::encode(array(
	        	'status'=>'success',
	        	'patient_balance'=> PatientBalance::getCurrentBalance($patient_id),
	    	));	
 		} else { // Есть ошибки
		    echo CJSON::encode(array(
	        	'status'=>'errors',
	        	'errors'=>$errors,
	    	));	
 		}
	}


	public function actionUpdate()
	{
		$id 				= (int)$_POST['transaction_id'];
		$patient_id 		= (int)$_POST['patient_id'];
		$patient_history_id = (int)$_POST['patient_history_id'];
		$date_visit 		= (string)$_POST['date_visit'];
		$sum 				= (float)$_POST['sum'];
		$comment 			= (string)$_POST['comment'];
		$errors = array();
 		$transaction = NULL;
 		$patient_history = NULL;

 		if($patient_history_id==0)
 			$errors[] = 'ID Визита пациента не может быть пустым.';
 		else
 			$patient_history = PatientsHistory::model()->findByPk($patient_history_id);

 		if($patient_history == NULL)
 			$errors[] = 'Визит пациента не найден.';

 		if($date_visit == "" || mb_strlen($date_visit) > 10)
 			$errors[] = 'Заполните дату в верном формате.';
 		

 		if($id==0){
 			$transaction = new PatientBalance;
 		} else {
 			$transaction = PatientBalance::model()->findByPk($id);
 			
 			if($transaction == NULL)
 				$errors[] = 'Транзакция не найдена.';
 		}


 		if(empty($errors)){
 			$transaction->patient_id 		 = $patient_id;
 			$transaction->patient_history_id = $patient_history_id;
 			$transaction->operation  		 = 1;
 			$transaction->date       		 = $date_visit;
 			$transaction->sum 				 = $sum;
 			$transaction->comment 			 = $comment;
 			$transaction->save();

	        echo CJSON::encode(array(
	        	'status'=>'success',
	        	'transaction_id'=>$transaction->id,
	        	'date_visit'=>$transaction->date,
	        	'datetime_visit'=>PatientBalance::getPrettyDate($transaction->patientHistory->datetime_visit),
	        	'visit'=>$transaction->patient_history_id,
	        	'sum' => $transaction->sum,
	        	'sum_view'=>PatientBalance::getSumOperation($transaction->sum, $transaction->operation),
	        	'comment'=>$transaction->comment,
	        	'patient_balance'=> PatientBalance::getCurrentBalance($patient_id),
	    	));	
 		} else { // Есть ошибки
		    echo CJSON::encode(array(
	        	'status'=>'errors',
	        	'errors'=>$errors,
	    	));	
 		}
	}

	public function actionGetinfo()
	{

		$id = (int)$_POST['transaction_id'];
		$transaction = $this->loadModel($id);

		if($transaction != NULL){
	        echo CJSON::encode(array(
	        	'status'=>'success',
	        	'transaction_id'=>$transaction->id,
	        	'date_visit'=>$transaction->date,
	        	'datetime_visit'=>PatientBalance::getPrettyDate($transaction->patientHistory->datetime_visit),
	        	'visit'=>$transaction->patient_history_id,
	        	'visit_select'=> Patients::getSelectListPatientVisits($transaction->patient_id, $transaction->patient_history_id),
	        	'sum' => $transaction->sum,
	        	'sum_view'=>PatientBalance::getSumOperation($transaction->sum, $transaction->operation),
	        	'comment'=>$transaction->comment,
	    	));
		} else {
	        echo CJSON::encode(array(
	        	'status'=>'errors',
	        	'errors'=>'Ошибка получения данных о транзакции #' . $id . '.',
	    	));	
		}
        
        Yii::app()->end();
	}
	


	public function actionDelete()
	{
		if(User::checkPermissionEditTransactions()){
			$id = (int)$_POST['transaction_id'];
			$patient_id = (int)$_POST['patient_id'];
			$transaction = $this->loadModel($id);

			if( $transaction->delete() ){
	            echo CJSON::encode(array(
	            	'status'=>'success',
	            	'patient_balance' => PatientBalance::getCurrentBalance($patient_id),
	            	));
			} else {
	            echo CJSON::encode(array(
	            	'errors'=>'Ошибка удаления транзакции.',
	        	));
			}
		}

        Yii::app()->end();
	}


	public function loadModel($id)
	{
		$model=PatientBalance::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		return $model;
	}

}
