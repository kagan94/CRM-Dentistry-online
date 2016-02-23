<?php

class HistoryController extends Controller
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
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array(),
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','create','update','index','view','FindPatientFio','SavePatientHistory','getWorkPlace'),
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


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	public function actionCreate()
	{

		$model=new PatientsHistory;

        if(isset($_GET['patient_id'])){
            $model->patient_id = (int)$_GET['patient_id'];
        }

		if(isset($_POST['PatientsHistory']))
		{
			$_POST['PatientsHistory']['date_added'] = date("Y-m-d");

			$model->attributes=$_POST['PatientsHistory'];
			
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
            
            if(Yii::app()->request->isAjaxRequest){
                echo 'success';
                Yii::app()->end();
            }
            // if($model->save())
			  // $this->redirect(array('view','id'=>$model->id));
		}

        if(Yii::app()->request->isAjaxRequest)
            $this->renderPartial('create',array('model'=>$model),false, false);
        else
            $this->render('create',array('model'=>$model));

//		$this->render('create',array(
//			'model'=>$model,
//		));
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['PatientsHistory']))
		{
			$_POST['PatientsHistory']['date_modifidied'] = date("Y-m-d");
			$model->attributes=$_POST['PatientsHistory'];

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

            if($model->save()){
                if(Yii::app()->request->isAjaxRequest){
                    echo 'success';
                    Yii::app()->end();
                }
            }
		}

        $criteria=new CDbCriteria;
        $criteria->select='order_service_id, service_id, quantity, price, title';
        $criteria->condition = 'patient_history_id = "'. $model->id .'"';
        $allPatientHisotoryServices = OrderServices::model()->findAll($criteria);

        if(Yii::app()->request->isAjaxRequest)
            $this->renderPartial('update',array(
                'model'=>$model,
                'allPatientHisotoryServices'=>$allPatientHisotoryServices,
            ),false, true);
        else
            $this->render('update',array(
                'model'=>$model,
                'allPatientHisotoryServices'=>$allPatientHisotoryServices,
            ));
    }


	public function actionDelete($id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "operation = 1 AND patient_history_id LIKE :patient_history_id";
		$criteria->params = array(":patient_history_id"=>$id);
		$count_patient_transactions = PatientBalance::model()->findAll($criteria);

		if(count($count_patient_transactions) > 0 ){
     		throw new CHttpException(400, "Вы не можете удалить данный визит, так как пациент уже делал оплату по данному визиту.");
		} else {
			$this->loadModel($id)->delete();
			
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}


	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('PatientsHistory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}


	public function actionAdmin()
	{
		$model=new PatientsHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PatientsHistory']))
			$model->attributes=$_GET['PatientsHistory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PatientsHistory the loaded model
	 * @throws CHttpException
	 */

	public function loadModel($id)
	{
		$model=PatientsHistory::model()->findByPk($id);

        if(isset($model->patient_id)){
            $criteria=new CDbCriteria;
            $criteria->select='fio';
            $criteria->condition='id=:postID';
            $criteria->params=array(':postID'=>$model['patient_id']);
            $getFio = Patients::model()->find($criteria);

            $model->patient_fio = $getFio['fio'];
        }


        // Корректное отображение времени в заданном формате
        if(isset($model->datetime_visit)){

            $model->datetime_visit = substr($model->datetime_visit, 0, 16);
        	
        	if($model->datetime_visit == "0000-00-00 00:00")
        		 $model->datetime_visit = '';
        }


        // Присваиваим скидку 
        $discount = DiscountHistory::model()->findByAttributes(array('patient_history_id'=>$id));

        if(isset($discount))
        	$model->discount_id = $discount->discount_id;


		if($model===null)
			throw new CHttpException(404,'Запрашиваемая Вами страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PatientsHistory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='patients-history-form')
		{
			// echo "<pre>";
			// echo var_dump($model);
			// echo "</pre>";
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionFindPatientFio()
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
        {
            /* q is the default GET variable name that is used by
            / the autocomplete widget to pass in user input
            */
            $name = $_GET['q'];
            // this was set with the "max" attribute of the CAutoComplete widget
            $limit = min($_GET['limit'], 50);
            $criteria = new CDbCriteria;
            $criteria->condition = "fio LIKE :sterm";
            $criteria->params = array(":sterm"=>"%$name%");
            $criteria->limit = $limit;
            $patients = Patients::model()->findAll($criteria);
            $returnVal = '';

            foreach($patients as $patient)
            {
                $returnVal .= $patient->getAttribute('fio').'|'
                    .$patient->getAttribute('id')."\n";
            }

            echo $returnVal;
        }
    }

  

    public function actionSavePatientHistory() {

    	if(isset($_POST['PatientsHistory']) && Yii::app()->request->isAjaxRequest) {
			// (int)$_POST['PatientsHistory']['patient_id'] != 0
    		$id = (int)$_POST['PatientsHistory']['id'];
			$successAddedServices = array();
			$successDeletedServices = array();


			 // Дата изменения услуг
			if($id > 0){
				$model = $this->loadModel($id);
				$_POST['PatientsHistory']['date_modifidied'] = date("Y-m-d");
			} else {
				$model = new PatientsHistory;
				$_POST['PatientsHistory']['date_added'] = date("Y-m-d");
			}

			if(User::checkPermissionEditServices($model->datetime_visit)){
					// Если статус записи не определен, или визит отменен. Некоторые данные удаляем. 
				$status_record = (int)($_POST['PatientsHistory']['status_record_id']);

				if($status_record == 0 OR $status_record == 3){
					$_POST['PatientsHistory']['duration'] = '';
					$_POST['PatientsHistory']['services'] = array();
					$_POST['PatientsHistory']['discount_id'] = null;

					$model->discount = 0;

					DiscountHistory::model()->deleteAllByAttributes(array('patient_history_id'=>$model->id));
					OrderServices::model()->deleteAllByAttributes(array('patient_history_id'=>$id));
				}	
			} else {
				$_POST['PatientsHistory']['datetime_visit'] = $model->datetime_visit;
			}

			$model->attributes = $_POST['PatientsHistory'];

			if($model->affiliate_id == null || $model->work_place_id == null){
				$model->work_place_id = null;
			}
			
			if(!$model->validate()){
                foreach($model->getErrors() as $attribute=>$error)
                    $errors[CHtml::activeId($model,$attribute)]=$error[0];
               	echo CJSON::encode($errors);
                Yii::app()->end();
            } else if($model->save()){ // Patient_history_id сохранен и доступен для дальнейших обновлений
        		
            	if(User::checkPermissionEditServices($model->datetime_visit)){
					$post_services = $_POST['PatientsHistory']['services'];

					if(count($post_services)){
						foreach ($post_services['service_id'] as $service_key => $service_id_value) {
							$data = '';

							if((int)$service_id_value > 0 && (int)$post_services['quantity'][$service_key] > 0 && (int)$post_services['key'][$service_key] < 1) { 
								// Кол-во, id услуги есть, ключа нет

								// Чистим от дубликатов
								$count_service = OrderServices::model()->findAll(array(
									'condition'=>'service_id=:service_id AND patient_history_id=:patient_history_id',
									'params'=>array(':service_id'=>(int)$service_id_value, ':patient_history_id'=>$model->id)
									));

								if(count($count_service) == 0) {

									$saveNewService = new OrderServices;
									$data = $this->getServicePriceAndTitle((int)$service_id_value);

									$saveNewService->patient_history_id = $model->id;
									$saveNewService->service_id = (int)$service_id_value;
									$saveNewService->quantity = (int)$post_services['quantity'][$service_key];
									$saveNewService->title = $data['title'];
									$saveNewService->price = $data['price'];
									$saveNewService->total = (int)$post_services['quantity'][$service_key] * (int)$data['price'];

									if($saveNewService->save()){
										$successAddedServices[$service_key] = $saveNewService->order_service_id;
									}
								} else {
									// Удаляем лишние id, которые не добавились
									$successDeletedServices[] = $service_key;
								}
							} else if((int)$service_id_value > 0 && (int)$post_services['quantity'][$service_key] > 0 && (int)$post_services['key'][$service_key] > 0) { 
								// Кол-во, id услуги и ключ есть

								if((int)$service_id_value == $this->getSeviceIdByOrderServiceId((int)$post_services['key'][$service_key])) {

									// Обновляем услугу по ключу, если id услуги одинаковые
									$findService = OrderServices::model()->findByPk((int)$post_services['key'][$service_key]);
			 						$data = $this->getServicePriceAndTitle((int)$service_id_value);
									
									$findService->patient_history_id = $model->id;
									$findService->service_id = (int)$service_id_value;
									$findService->quantity = (int)$post_services['quantity'][$service_key];
									$findService->title = $data['title'];
									$findService->price = $data['price'];
									$findService->total = (int)$post_services['quantity'][$service_key] * (int)$data['price'];
									$findService->save();
								}

							} else if( ((int)$service_id_value < 1 || (int)$post_services['quantity'][$service_key] < 1 ) && (int)$post_services['key'][$service_key] > 0) {

								OrderServices::model()->deleteByPk((int)$post_services['key'][$service_key]);
								$successDeletedServices[] = $service_key;
									
							} else {
								// Удаляем лишние id, которые не добавились
								$successDeletedServices[] = $service_key;
							}
						}

						 // Чистим массив от дубликатов
						foreach ($post_services['service_id'] as $service_key => $service_id_value) {

							if((int)$service_id_value > 0 && (int)$post_services['quantity'][$service_key] > 0 && (int)$post_services['key'][$service_key] > 0) { 
								// Кол-во, id услуги и ключ есть

								if((int)$service_id_value != $this->getSeviceIdByOrderServiceId((int)$post_services['key'][$service_key])) {
									// Проверяем на дубликаты выбранную услугу с ключем, если копий нету => обновляем ее

									// Ищем дубликаты
									$count_service_copy = OrderServices::model()->findAll(array(
										'condition'=>'service_id=:service_id AND patient_history_id=:patient_history_id',
										'params'=>array(':service_id'=>(int)$service_id_value, ':patient_history_id'=>$model->id)
										));

									if(count($count_service_copy) == 0) {
										$findService = OrderServices::model()->findByPk((int)$post_services['key'][$service_key]);
				 						$data = $this->getServicePriceAndTitle((int)$service_id_value);
										
										$findService->patient_history_id = $model->id;
										$findService->service_id = (int)$service_id_value;
										$findService->quantity = (int)$post_services['quantity'][$service_key];
										$findService->title = $data['title'];
										$findService->price = $data['price'];
										$findService->total = (int)$post_services['quantity'][$service_key] * (int)$data['price'];

										$findService->save();
									} else {
										// Удаляем лишние id, которые не добавились
										OrderServices::model()->deleteByPk((int)$post_services['key'][$service_key]);
										$successDeletedServices[] = $service_key;
									}
								}
							}
						}
					} 

					// Подсчитаем всю сумму
					$total_sum = $this->getTotalSum($model->id);

			        // Сохраняем скидку 
			        $discount_id = (int) $_POST['PatientsHistory']['discount_id'];

					if($discount_id > 0 AND $total_sum > 0){

						$discount_info = Discounts::model()->findByPk($discount_id);
			        	$discount = DiscountHistory::model()->findByAttributes(array('patient_history_id'=>$model->id));

			        	if($this->calculateByDiscountType($discount_info->type, $discount_info->size, $total_sum) > 0){
					        if(count($discount)>0){
					        	$discount->discount_id = $discount_id;
					        	$discount->title = $discount_info->title;
					        	$discount->total = $this->calculateByDiscountType($discount_info->type, $discount_info->size, $total_sum); 
					        	$discount->save();

								$model->discount = $discount->total;

					        } else {
					        	$newDiscount = new DiscountHistory;
					        	$newDiscount->discount_id = $discount_id;
					        	$newDiscount->patient_history_id = $model->id;
					        	$newDiscount->title = $discount_info->title;
					        	$newDiscount->total = $this->calculateByDiscountType($discount_info->type, $discount_info->size, $total_sum); 
						        $newDiscount->save();

								$model->discount = $newDiscount->total;
					        }
			        	} else {
							DiscountHistory::model()->deleteAllByAttributes(array('patient_history_id'=>$model->id));
							$model->discount = 0;
			        	}

					} else {
						DiscountHistory::model()->deleteAllByAttributes(array('patient_history_id'=>$model->id));
						$model->discount = 0;
					}

					$model->total=$total_sum;
					$model->save();
				} else {
					$total_sum = $this->getTotalSum($model->id);
				} // Конец checkPermissionEditServices 

				PatientBalance::updateBalanceTransaction($model->patient_id, $model->id, $model->datetime_visit, (int)($model->total - $model->discount) );

				echo CJSON::encode(array(
	               'status'=>'success',
                   'patient_history_id'=>$model->id,//$model->id,
	               'successAddedServices'=>$successAddedServices,
	               'successDeletedServices'=>$successDeletedServices,
	               'total_sum'=>$total_sum,
	               'disable_services'=>(User::checkPermissionEditServices($model->datetime_visit)) ? 0 : 1,
	        	   'patient_balance'=> PatientBalance::getCurrentBalance($model->patient_id),
		        ));
			}
    	}    	
	}


    public function getServicePriceAndTitle($id){
        $services = Services::model()->findByPk($id);

        if(isset($services['title'])) {
            return array('title' => $services['title'], 'price' => $services['price']);
        } else {
            return false;
        }
    }


    public function getSeviceIdByOrderServiceId($id) {
    	
        $services = OrderServices::model()->findByPk($id);

        if(isset($services['service_id'])) {
            return $services['service_id'];
        } else {
            return 0;
        }

    }


	public function calculateByDiscountType($discount_type, $discount_size, $total_sum)
	{
		$total = 0;

    		// фильтруем по типу скидки 
    	if($discount_type == 1){   // скидка в Грн.
    		if(($total_sum-$discount_size) > 0){
				$total = $discount_size;
    		} else {
    			$total = 0;
    		}
    	} else if($discount_type == 2){  // скидка в % 

    		if( $total_sum * (1-( ($discount_size)/100) )  > 0){
				$total = $total_sum * ($discount_size/100);
    		} else {
    			$total = 0;
    		}
    	}

		return (int) $total;
	}


    public function actionGetWorkPlace() {
        
		$affiliate_id = (int)Yii::app()->request->getParam('work_place_id');

        $this->renderPartial('_check_working_place', array(
            'affiliate_id' => $affiliate_id,
        ));

    }


	private function getTotalSum($patient_history_id){
			// Подсчитаем всю сумму 
	    $command=Yii::app()->db->createCommand();
	    $command->select('SUM(total) AS summ');
	    $command->from('order_services');
	    $command->where('patient_history_id=:patient_history_id', array(':patient_history_id'=>$patient_history_id));
	    
	    return $command->queryScalar();
	}
			        	
}
