<?php

/**
 * This is the model class for table "patient_balance".
 *
 * The followings are the available columns in table 'patient_balance':
 * @property integer $id
 * @property integer $patient_id
 * @property integer $patient_history_id
 * @property integer $operation
 * @property string $date
 * @property double $sum
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Patients $patient
 * @property PatientsHistory $patientHistory
 */
class PatientBalance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'patient_balance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('patient_id, patient_history_id, operation, date, sum', 'required'),
			array('patient_id, patient_history_id, operation', 'numerical', 'integerOnly'=>true),
			array('sum', 'numerical'),
			array('comment', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, patient_id, patient_history_id, operation, date, sum, comment', 'safe', 'on'=>'search'),
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
			'patient' => array(self::BELONGS_TO, 'Patients', 'patient_id'),
			'patientHistory' => array(self::BELONGS_TO, 'PatientsHistory', 'patient_history_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'patient_id' => 'Карта пациента',
			'patient_history_id' => 'Визит пациента',
			'operation' => 'Операция: 1- приход, 2-расход',
			'date' => 'Дата операции',
			'sum' => 'Сумма',
			'comment' => 'Комментарий к операции',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('patient_id',$this->patient_id);
		$criteria->compare('patient_history_id',$this->patient_history_id);
		$criteria->compare('operation',$this->operation);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('sum',$this->sum);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PatientBalance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function getStatusOperation($operation){
		if ($operation == 1)
			return "<div class=\"debit\">Поступление от пациента</div>";

		if ($operation == 2) // за лечение
			return "<div class=\"credit\">Оплата за лечение</div>";	
	}


	public static function getSumOperation($sum, $operation){
		if ($operation == 1)
			return "<div class=\"debit\">{$sum}</div>";

		if ($operation == 2) // за лечение
			return "<div class=\"credit\">-{$sum}</div>";	
	}


	public static function getCurrentBalance($patient_id){

		$current_balance = Yii::app()->db->createCommand('
		  select debits-credits as balance
		  from (
		    SELECT 
		    patient_id,
		    SUM(case when operation="1" then sum else 0 end) AS debits, 
		    SUM(case when operation="2" then sum else 0 end) AS credits
		    FROM `patient_balance`
		    GROUP BY patient_id
		  ) a
		  where patient_id=' . $patient_id
		)->queryRow();

		$current_balance['balance'] = (float)$current_balance['balance'];

		if($current_balance['balance'] >= 0){
			return "<div class=\"positive_balance\">" . $current_balance['balance'] . " грн.</div>"; 
		} else {
			return "<div class=\"negative_balance\">" . $current_balance['balance'] . " грн.</div>"; 
		}
	}


	public static function updateBalanceTransaction($patient_id, $patient_history_id, $date, $sum){
	  	$credit_transaction = PatientBalance::model()->findByAttributes(array('patient_history_id'=>$patient_history_id, 'operation'=>2));

		if(count($credit_transaction) == 0){
			$credit_transaction = new PatientBalance;
			$credit_transaction->patient_id = $patient_id;
			$credit_transaction->patient_history_id = $patient_history_id;
			$credit_transaction->operation = 2;
			$credit_transaction->date = $date; //datetime_visit
			$credit_transaction->sum = $sum;

		    if($sum > 0)
		    	$credit_transaction->save(); 
		} else {
			$credit_transaction->date = $date; //datetime_visit
			$credit_transaction->sum = $sum;

			if($sum > 0)
				$credit_transaction->save(); 
			else 
				$credit_transaction->delete();
		}
	}
	

	public static function getPrettyDate($date){
		return date("Y-m-d H:i", strtotime($date));
	}


}
