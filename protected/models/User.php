<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $name
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, name', 'required'),
			array('login', 'length', 'max'=>20),
            array('login', 'unique'),
            array('password, name', 'length', 'max'=>50),
            //array('password', 'authenticate'),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, password, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Логин',
			'password' => 'Пароль',
			'name' => 'Ф.И.О.',
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
        $criteria->compare('login',$this->login,true);
        $criteria->compare('name',$this->name,true);
        /* $criteria->compare('password',$this->password,true); */

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


		// Используется на стр. визитов, чтобы не могли менять услуги после истечения 2-х дней.
	public static function checkPermissionEditServices($datetime_visit){

		if(Yii::app()->user->getName()=='admin'){
			return true;
		} else if( strtotime(date("Y-m-d H:i")) < (strtotime($datetime_visit)+(60*60*24*2)) OR $datetime_visit == NULL){
			return true;
		} else {
			return false;
		}
	}

		// Используется на стр. визитов, чтобы не могли менять транзакции
	public static function checkPermissionEditTransactions(){

		if(Yii::app()->user->getName()=='admin'){
			return true;
		} else {
			return false;
		}
	}

}
