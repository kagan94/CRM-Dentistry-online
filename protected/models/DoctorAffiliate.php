<?php

/**
 * This is the model class for table "doctor_affiliate".
 *
 * The followings are the available columns in table 'doctor_affiliate':
 * @property integer $id
 * @property integer $doctor_id
 * @property integer $affiliate_id
 */
class DoctorAffiliate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'doctor_affiliate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id', 'required'),
			array('id, doctor_id, affiliate_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, doctor_id, affiliate_id', 'safe', 'on'=>'search'),
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
			 // 'doctors' => array(self::BELONGS_TO, 'Doctors', 'id'),
			 // 'affiliates' => array(self::BELONGS_TO, 'Affiliate', 'id'),
			 
            'doctor' => array(self::BELONGS_TO, 'Doctors', 'doctor_id'),
            'affiliate' => array(self::BELONGS_TO, 'Affiliate', 'affiliate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'doctor_id' => 'Doctor',
			'affiliate_id' => 'Affiliate',
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
		$criteria->compare('doctor_id',$this->doctor_id);
		$criteria->compare('affiliate_id',$this->affiliate_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoctorAffiliate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
