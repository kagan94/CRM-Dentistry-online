<?php

/**
 * This is the model class for table "affiliate".
 *
 * The followings are the available columns in table 'affiliate':
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $adress
 */
class Affiliate extends CActiveRecord
{
	public $working_places;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'affiliate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, city, adress', 'required'),
			array('name', 'length', 'max'=>150),
			array('city, adress', 'length', 'max'=>100),
			array('working_places', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, city, adress', 'safe', 'on'=>'search'),
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
            'patientsHistories' => array(self::HAS_MANY, 'PatientsHistory', 'affiliate_id'),
        	'doctorAffiliates' => array(self::HAS_MANY, 'DoctorAffiliate', 'affiliate_id'),
            'workingPlaces' => array(self::HAS_MANY, 'WorkingPlaces', 'affiliate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'city' => 'Город',
			'adress' => 'Адрес',
			'working_places'=> 'Рабочее место',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('adress',$this->adress,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Affiliate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
