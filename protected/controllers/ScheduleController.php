<?php

class ScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
				'actions'=>array('index','getData','getworkplace', 'addnewvisit','getAllVisits'),
				'users'=>array('@'),
			),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
			array('deny',  // deny all users
				// 'actions'=>array(''),
				'users'=>array('*'),
			),
		);
	}


	public function actionIndex()
	{
		$data = array();

		$doctor = (int)Yii::app()->request->getParam('doctor');
		$affiliate = (int)Yii::app()->request->getParam('affiliate');
		$work_place = (int)Yii::app()->request->getParam('work_place');

		if($doctor != 0)
			$data['doctor'] = $doctor;
		else
			$data['doctor'] = 0;

		if($affiliate != 0)
			$data['affiliate'] = $affiliate;
		else
			$data['affiliate'] = 0;		
		
		if($work_place != 0)
			$data['work_place'] = $work_place;
		else
			$data['work_place'] = 0;		
		
        $data['events_url'] = $this->createUrl('schedule/getAllVisits', array('doctor'=>$doctor, 'affiliate'=>$affiliate, 'work_place'=>$work_place) );

		$new_visit = new FastAddingPatient;
		$this->performAjaxValidation($new_visit);

		$this->render('view',array(
			'data'=> $data,
			'new_visit' => $new_visit,
		));
	}


    public function actionGetWorkPlace() {
        
		$affiliate_id = (int)Yii::app()->request->getParam('aff_id');

        $this->renderPartial('_check_working_place', array(
            'affiliate_id' => $affiliate_id,
        ));

    }


    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='fast_adding_patient')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionAddNewVisit(){

		$model=new FastAddingPatient;             
		$this->performAjaxValidation($model);  

		if(isset($_POST['FastAddingPatient']))
		{
			$_POST['FastAddingPatient']['work_place_id'] = isset($_POST['PatientsHistory']['work_place_id']) ? $_POST['PatientsHistory']['work_place_id'] : NULL ;
			$model->attributes=$_POST['FastAddingPatient'];
			$valid=$model->validate();            
			
			if($valid){
				if($model->new_patient == 0){
					$patient=Patients::model()->findByPk($model->patient_id);

					if($patient === NULL){
						echo '{"FastAddingPatient_patient_fio":["Пациент с данным Ф.И.О. не найден."]}';
			        	Yii::app()->end();
					} else {
						$new_visit = $this->setNewPatientHistory($patient->id, $model);
						
						if($new_visit->save()){
						    echo CJSON::encode(array(
						        'status'=>'success',
						        'patient'=>$this->getEventArray($new_visit, $this->getListWorkingPlaces() ),
						    ));
					    } else {
							echo '{"FastAddingPatient_patient_fio":["Ошибка добавления нового визита пациента."]}';
						}
			        	
			        	Yii::app()->end();
					}
			     // Create a new patient
				} else {
					$new_patient = new Patients;
					$new_patient->fio = $model->patient_fio;
					$new_patient->phone = $model->phone;
					$new_patient->date_birthday = $model->date_birthday;
					$new_patient->date_added = date("Y-m-d");					

					if($new_patient->save()){
						$new_visit = $this->setNewPatientHistory($new_patient->id, $model);

						if($new_visit->save()){

						    echo CJSON::encode(array(
						        'status'=>'success',
						        'patient'=>$this->getEventArray($new_visit, $this->getListWorkingPlaces() ),
						    ));
					    } else {
							echo '{"FastAddingPatient_patient_fio":["Ошибка добавления нового визита для нового пациента."]}';
						}
					} else {
						echo '{"FastAddingPatient_patient_fio":["Ошибка сохранения нового пациента."]}';
					}
		        	
		        	Yii::app()->end();
				}

		    } else {
		        $error = CActiveForm::validate($model);
		        if($error!='[]')
		            echo $error;

		        Yii::app()->end();
		    }
		}
    }


    private function setNewPatientHistory($patient_id, $model){

		$new_visit = new PatientsHistory;
		$new_visit->patient_id = $patient_id;
		$new_visit->doctor_id = $model->doctor_id;
		$new_visit->affiliate_id = $model->affiliate_id;
		
		if($model->work_place_id !== NULL)
			$new_visit->work_place_id = $model->work_place_id;

		$new_visit->datetime_visit = $model->datetime_visit;
		$new_visit->status_record_id = 1; // 1 = Статус записи - пациент записан
		$new_visit->date_added = date("Y-m-d");
		$new_visit->comment = $model->comment;

    	return $new_visit;
    }


    private function getListWorkingPlaces(){

         // Раскраска записей в зависимости от кресла
		$places = WorkingPlaces::model()->findAll();
		 
		// при помощи listData создаем массив вида $ключ=>$значение
		$list_places = CHtml::listData($places, 'id', 'id');

		$colors = array('rgb(78, 197, 255)','green','#6F7825','#E64B7D');
		$i = 0;

		foreach ($list_places as $key => $value) {
		    $list_places[$key] = $colors[$i];
		    
		    if($i==3){
		        $i = 0;
		    } else {
		    	$i++;
		    }
		}

		return $list_places;
	}

    private function getEventArray($patient_history, $list_places = array() ){

		$desc = "";
		$color = 'rgb(114, 173, 73)';
		
		if($patient_history->doctor_id){
			$desc .= 'Врач: <b>' . $patient_history->doctor->fio . '</b><br>';
		} else {
			$desc .= "Врач: <b>не выбран.</b><br>";
		}

		if($patient_history->affiliate_id){
			$desc .= 'Филиал: <b>' . PatientsHistory::getAffiliateName($patient_history->affiliate) . ' </b>';

			if($patient_history->work_place_id){
				$desc .= '(' . $patient_history->workPlace->title . ')<br>';

				$color = $list_places[$patient_history->work_place_id];
			} else { 
				$desc .= '<br>';
			}
		} else {
			$desc .= "Филиал: <b>не выбран.</b><br>";
		}

		if($patient_history->status_record_id){
			$desc .= 'Статус: <b>' . $patient_history->statusRecord->title . '</b><br>';

			if($patient_history->status_record_id == 3) { // Задаем цвет если пациент НЕ ПРИШЕЛ
				$color = 'black';
			} else if($patient_history->status_record_id == 2) { // Задаем цвет если пациент Пролечен
				$color = "rgb(85, 85, 85)";
			} else if($patient_history->status_record_id == 4) { // Задаем цвет если пациент Отменен
				$color = "rgb(147, 94, 30)";
			}
		} else {
			$desc .= "Статус: <b>не выбран.</b><br>";
		}

		if(Yii::app()->user->getName()=='admin'){
			if($patient_history->total){
				$desc .= 'Сумма (в т.ч. скидка): <b><u>' . ($patient_history->total - $patient_history->discount) . ' грн. </u></b><br>';
			} else {
				$desc .= "Сумма: <b>-</b><br>";
			}
		}

		if(strlen(trim($patient_history->comment)) > 0) {
			$desc .= '<br>Комментарий: <b><i>' . $patient_history->comment . '</i></b><br>';
		}

		$end_time = '';
		
		if((int)$patient_history['duration'] != 0){
			$end_time = date("Y-m-d H:i:s", strtotime( $patient_history['datetime_visit'] ) + (60*$patient_history['duration']) );
		} else {
			$end_time = date("Y-m-d H:i:s", strtotime( $patient_history['datetime_visit'] ) + (60*30) );
		}
		
		return array(
			'title'=>$patient_history->patient->fio,
       		'description'=> $desc,
			'start'=>$patient_history->datetime_visit, //'2015-10-10 10:00:00',
			'end'=> $end_time,
			'color'=> $color, //$colors[ (int)$history['doctor_id'] ], //#CC0000',
			'url'=> $this->createUrl("patient/history/update/id/$patient_history->id"),
		);
	}


	public function actionGetAllVisits(){  // $doctor = 0, $affiliate = 0, $work_place = 0

		$data = array();
		$doctor = (int)Yii::app()->request->getParam('doctor');
		$affiliate = (int)Yii::app()->request->getParam('affiliate');
		$work_place = (int)Yii::app()->request->getParam('work_place');

        $criteria=new CDbCriteria;
        $criteria->condition = "datetime_visit BETWEEN '".date('Y-m-d', strtotime('-1 week'))."' AND '" . date('Y-m-d', strtotime('+1 months')) . "' ";
		$criteria->order = 'affiliate_id DESC, work_place_id DESC';

		if($doctor != 0){
			$data['doctor'] = $doctor;
			$criteria->compare('doctor_id',$doctor);
		} else {
			$data['doctor'] = 0;
		}

		if($affiliate != 0){
			$data['affiliate'] = $affiliate;
			$criteria->compare('affiliate_id', $affiliate);
			// $criteria->condition = "affiliate_id = '" . $affiliate . "'";
		} else {
			$data['affiliate'] = 0;		
		}

		if($work_place != 0){
			$data['work_place'] = $work_place;
			$criteria->compare('work_place_id', $work_place);
		} else {
			$data['work_place'] = 0;		
		}

        $items = array();


		$list_places = $this->getListWorkingPlaces(); // It needs for coloring event that depends on working place

		$histories = PatientsHistory::model()->findAll($criteria);

		foreach ($histories as $history) {
			$items[] = $this->getEventArray($history, $list_places); // return array(title, start, end, color, url)
		}

        echo CJSON::encode($items);
	}
}
