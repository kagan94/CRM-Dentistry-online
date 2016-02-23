<?php

/**
 * This is the model class for table "order_services".
 *
 * The followings are the available columns in table 'order_services':
 * @property integer $order_service_id
 * @property integer $patient_history_id
 * @property integer $service_id
 * @property string $title
 * @property integer $price
 * @property integer $quantity
 * @property integer $total
 *
 * The followings are the available model relations:
 * @property PatientsHistory $patientHistory
 * @property Services $service
 */
class OrderServices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_services';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id', 'required'),
			array('patient_history_id, service_id, price, quantity, total', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_service_id, patient_history_id, service_id, title, price, quantity, total', 'safe', 'on'=>'search'),
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
			'patientHistory' => array(self::BELONGS_TO, 'PatientsHistory', 'patient_history_id'),
			'service' => array(self::BELONGS_TO, 'Services', 'service_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order_service_id' => 'Order Service',
			'patient_history_id' => 'Patient History',
			'service_id' => 'Service',
			'title' => 'Title',
			'price' => 'Price',
			'quantity' => 'Quantity',
			'total' => 'Total',
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

		$criteria->compare('order_service_id',$this->order_service_id);
		$criteria->compare('patient_history_id',$this->patient_history_id);
		$criteria->compare('service_id',$this->service_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderServices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
