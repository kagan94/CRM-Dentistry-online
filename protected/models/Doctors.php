<?php

/**
 * This is the model class for table "doctors".
 *
 * The followings are the available columns in table 'doctors':
 * @property integer $id
 * @property string $fio
 * @property string $photo
 * @property integer $phone
 * @property string $email
 */
class Doctors extends CActiveRecord
{
	public $doctor_image;  // атрибут для хранения загружаемой картинки
	public $del_image; // атрибут для удаления уже загруженной картинки
	public $affiliates; // Филиалы

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'doctors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fio, phone', 'required'),
			array('phone', 'numerical', 'integerOnly'=>true),
			array('fio', 'length', 'max'=>200),
            array('direction', 'length', 'max'=>100),
            array('photo', 'length', 'max'=>50),
            array('email', 'length', 'max'=>100),
            array('del_image', 'boolean'),
            array('doctor_image', 'file', 
            	'allowEmpty'=>'true', 
            	'types'=>'jpg, gif, png', 
            	'safe' => true,
      			'maxSize'=>1024 * 1024 * 5, // 5 MB
            	'tooLarge'=>'Файл весит больше 5 MB. Пожалуйста, загрузите файл меньшего размера.',
        	),
        	array('affiliates, trash', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fio, phone, email, direction', 'safe', 'on'=>'search'),
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
            'doctorAffiliates' => array(self::HAS_MANY, 'DoctorAffiliate', 'doctor_id'),
			// 'affs' => array(self::HAS_MANY, 'Affiliate', 'doctor_id', 'through' => 'doc_affs'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fio' => 'Ф.И.О.',
            'direction' => 'Направление',
			'photo' => 'Фото врача',
			'phone' => 'Телефон',
			'email' => 'Email',
		    'doctor_image' => 'Фотография врача',
		    'del_image'=>'Удалить картинку?',
		    'affiliates'=>'Рабочие филиалы',
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

		if(count($data)>0 AND $data['trash'] == 1){
			$criteria->compare('trash', 1);
		} else {
			$criteria->compare('trash', 0);
		}

		$criteria->compare('id',$this->id);
		$criteria->compare('fio',$this->fio,true);
        $criteria->compare('direction',$this->direction,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Doctors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	 public static function getAffiliates($model){

        $aff_names = "";

		foreach ($model as $aff_info) {
			$aff_names .= PatientsHistory::getAffiliateName($aff_info->affiliate) . "<br>";
		}

	 	return $aff_names ;
	 }
	 

	 public static function getDoctorShortFIO($fio){

		$fio = mb_split(' ', trim(str_replace("  ", " ", $fio)) );  

		if(isset($fio[0]))
		  $fio[0] = mb_convert_case($fio[0], MB_CASE_TITLE, 'UTF-8');

		if(isset($fio[1]))
		  $fio[1] = mb_convert_case( mb_substr($fio[1], 0, 1, 'UTF-8') . ".",  MB_CASE_UPPER, "UTF-8");

		if(isset($fio[2]))
		  $fio[2] = mb_convert_case( mb_substr($fio[2], 0, 1, 'UTF-8') . ".",  MB_CASE_UPPER, "UTF-8");

	  	return implode(' ', $fio);
	 }

	
}
