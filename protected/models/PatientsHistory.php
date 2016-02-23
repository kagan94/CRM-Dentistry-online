<?php

/**
 * This is the model class for table "patients_history".
 */
class PatientsHistory extends CActiveRecord
{
    public  $patient_fio;
    public  $doctor_fio;
    public  $services = array();
    public  $getServiceHistory = array();
    public  $history_id;
    public  $discount_id;
    public  $total2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'patients_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('patient_id, status_record_id, doctor_id, affiliate_id, datetime_visit, work_place_id', 'required'),
			array('patient_id, doctor_id, affiliate_id, duration, status_record_id, work_place_id', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
			array('comment', 'length', 'max'=>250),
			array('datetime_visit, date_added, date_modifidied, patient_fio, doctor_fio, discount_id, work_place_id', 'safe'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, patient_id, doctor_id, affiliate_id, datetime_visit, duration, comment, date_added, date_modifidied, status_record_id, total, total2', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'diagnosesHistories' => array(self::HAS_MANY, 'DiagnosesHistory', 'patient_history_id'),
			'discountHistories' => array(self::HAS_MANY, 'DiscountHistory', 'patient_history_id'),
			'orderServices' => array(self::HAS_MANY, 'OrderServices', 'patient_history_id'),
			'affiliate' => array(self::BELONGS_TO, 'Affiliate', 'affiliate_id'),
			'doctor' => array(self::BELONGS_TO, 'Doctors', 'doctor_id'),
			'patient' => array(self::BELONGS_TO, 'Patients', 'patient_id'),
			'statusRecord' => array(self::BELONGS_TO, 'StatusRecord', 'status_record_id'),
			'toothHistories' => array(self::HAS_MANY, 'ToothHistory', 'patient_history_id'),
            'workPlace' => array(self::BELONGS_TO, 'WorkingPlaces', 'work_place_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(

			'id' => 'ID',
			'patient_id' => 'Ф.И.О. пациента',
			'doctor_id' => 'Лечащий врач',
			'affiliate_id' => 'Филиал',
			'total' => 'Стоимость лечения, грн.',
			'datetime_visit' => 'Время визита',
			'duration' => 'Продолжительность визита',
			'comment' => 'Диагноз',
			'status_record_id' => 'Статус записи',
            'discount_id' => 'Скидка',
            'work_place_id'=>'Рабочее место',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($data=array())
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		if( isset($data['search_fio']) ){
			$criteria->with = array('patient');
	        $criteria->compare('patient.fio',$this->patient_id, true);
		} else {
	        $criteria->compare('patient_id',$this->patient_id);
		}


        $criteria->compare('doctor_id',$this->doctor_id);
		$criteria->compare('affiliate_id',$this->affiliate_id);
        $criteria->compare('discount_id',$this->discount_id);
		$criteria->compare('datetime_visit',$this->datetime_visit,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('date_modifidied',$this->date_modifidied,true);
		$criteria->compare('status_record_id',$this->status_record_id);

		$criteria->select= "*, 
             CASE 
                  WHEN discount > 0 
                     THEN (total-discount) 
                  ELSE total
             END  as total2
		";

		$criteria->compare('total2', $this->total);

        $sort = new CSort();

        $sort->defaultOrder = 't.date_added DESC';
        $sort->attributes = array(
            'total2'=>array(
                    'asc'=>'total2',
                    'desc'=>'total2 desc',
            ),
            '*',
        );

		return new CActiveDataProvider($this, array(
	        'criteria' => $criteria,
	        'sort' => $sort,
	        'pagination' => array(
	            'pageSize' => 20,
	        ),
    	));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PatientsHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getAffiliate(){
        $affiliates = Affiliate::model()->findAll(array('order' => 'name'));

        foreach ($affiliates as $affiliate)
	        $data[$affiliate->id] = $affiliate->adress;

        return $data;
    }

    public static function getAffiliateName($affiliate){  
    	// На стр. визитов (стр. пациент), поиск по визитам, в расписании, в расписании форма быстрого добавления
        return $affiliate->adress;
    }


    public static function getAffiliateWorkingPlaces($id){
    	$data = array();
    	
    	$criteria = new CDbCriteria;
    	$criteria->condition = "affiliate_id = '$id'";
        $places = WorkingPlaces::model()->findAll($criteria);

        foreach ($places as $place)
        	$data[$place->id] = $place->title;

        return $data;
    }

    public static function getDoctors(){

    	$criteria = new CDbCriteria;
    	
        $criteria->condition = 'trash = 0';
		$criteria->order = 'fio';

		$allDoctors =  Doctors::model()->findAll($criteria);

		foreach ($allDoctors as $doctor) {
			$doctor->fio = Doctors::getDoctorShortFIO($doctor->fio);
		}

        $doctors = CHtml::listData( $allDoctors ,'id', 'fio');
        
        return $doctors;
    }

    public static function getServices(){
        $info = Services::model()->FindAll(array('order' => 'code'));
        
        return $info;
    }

    public static function getStatuses(){
        $statusesRecord = CHtml::listData(  StatusRecord::model()->findAll(array('order' => 'title')) ,'id', 'title');
        
        return $statusesRecord;
    }

    public static function getDiscountsList($id=0){

		$discounts = Discounts::model()->findAll(array('order' => 'type'));
    	$data = array();
    	$selected = "";

    	echo "<select id=\"discount\" style=\"float: none; width: 151px; \" name=\"PatientsHistory[discount_id]\">
	    <option value=\"\">Выберите скидку</option>";

    	if(count($discounts) > 0){

		    foreach ($discounts as $discount){

	        	if($discount->type == '1') $amount = ' грн.';
	    		else if ($discount->type == '2') $amount = '%';

	    		if($id > 0 && $id == $discount->discount_id){
	    			$selected = "selected";
	    		} else {
	    			$selected = "";
	    		}

		    	echo "<option value=\"{$discount->discount_id}\" discount-type=\"{$discount->type}\" discount-size=\"{$discount->size}\" {$selected}>{$discount->title} ({$discount->size}{$amount})</option>";

		    }
    	}

		echo "</select>";
    }


    public static function getShortDate($date){
        $newDate = substr($date, 0, 10);
        
        if($newDate != "0000-00-00")
        	echo $newDate;
    	else 
    		echo "";
    }

	public static function getPrettyDate($date){
		return date("Y-m-d h:i", strtotime($date));
	}

    public static function getPrettyDuration($duration){
        if($duration < 60) echo $duration." мин.";
        else if ($duration == 60) echo "1 час";
        else if ($duration > 60 && $duration < 120) echo "1 час ".($duration-60)." мин.";
        else if ($duration == 120) echo "2 часа";
    }

    public static function getServicePrice($id){
        $services = Services::model()->findByPk($id);

        if(isset($services['price'])) {
            return $services['price'];
        } else {
            return false;
        }
    }

    protected function beforeDelete()
    {
        if ($this->id) {
			OrderServices::model()->deleteAllByAttributes(array('patient_history_id'=>$this->id));
			DiscountHistory::model()->deleteAllByAttributes(array('patient_history_id'=>$this->id));

			$credit_transaction = PatientBalance::model()->findByAttributes(array('patient_history_id'=>$this->id, 'operation'=>2));

	 	 	if(count($credit_transaction) > 0)
	  			$credit_transaction->delete();
        }
        
        return parent::beforeDelete();
    }

}
