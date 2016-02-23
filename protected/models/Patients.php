<?php

/**
 * This is the model class for table "patients".
 *
 * The followings are the available columns in table 'patients':
 * @property integer $id
 * @property string $fio
 * @property integer $phone
 * @property integer $homephone
 * @property integer $gender
 * @property string $date_birthday
 * @property string $email
 * @property string $adress
 * @property integer $source_id
 * @property string $comment
 * @property string $date_added
 * @property string $date_modifidied
 *
 * The followings are the available model relations:
 * @property Sources $source
 * @property PatientsHistory[] $patientsHistories
 */
class Patients extends CActiveRecord
{
    public $patient_hist_count;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'patients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fio', 'required'),
			array('phone, homephone, gender, source_id', 'numerical', 'integerOnly'=>true),
			array('fio', 'length', 'max'=>150),
			array('email', 'length', 'max'=>100),
			array('adress', 'length', 'max'=>300),
			array('comment', 'length', 'max'=>400),
			array('allergic_reactions, diseases', 'length', 'max'=>500),
			array('date_birthday, allergic_reactions, diseases, date_added, date_modifidied', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fio, phone, homephone, gender, date_birthday, email, adress, patient_hist_count, source_id, comment, date_added, date_modifidied', 'safe', 'on'=>'search'),
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
			'source' => array(self::BELONGS_TO, 'Sources', 'source_id'),
			'patientsHistories' => array(self::HAS_MANY, 'PatientsHistory', 'patient_id'),
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
			'phone' => 'Телефон',
			'homephone' => 'Домашний телефон',
			'gender' => 'Пол',
			'date_birthday' => 'Дата Рождения',
			'email' => 'E-mail',
			'adress' => 'Адрес',
			'type_id' => 'Тип пациента',
			'source_id' => 'Источник',
			'comment' => 'Комментарий',
			'allergic_reactions' => 'Аллергические реакции',
			'diseases' => 'Перенесенные и сопутствующие заболевания',
            'patient_id' => 'Ф.И.О. пациента *',
            'doctor_id' => 'Лечащий врач *',
            'affiliate_id' => 'Филиал *',
            'total' => 'Стоимость лечения, грн.',
            'datetime_visit' => 'Время визита *',
            'duration' => 'Продолжительность визита',
            'record_comment' => 'Комментарий к записи',
            'status_record_id' => 'Статус записи *',
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
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('homephone',$this->homephone);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('date_birthday',$this->date_birthday,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('adress',$this->adress,true);
		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date_added',$this->date_added,true);
        $criteria->compare('date_modifidied',$this->date_modifidied,true);

        $post_table = PatientsHistory::model()->tableName();
        $patient_hist_count_sql = "(select count(*) from $post_table pt where pt.patient_id = t.id)";
        $criteria->select = array(
            '*',
            $patient_hist_count_sql . " as patient_hist_count",
        );

        $criteria->compare($patient_hist_count_sql, $this->patient_hist_count);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.date_added DESC',
                'attributes' => array(
                    'patient_hist_count' => array(
                        'asc' => 'patient_hist_count ASC',
                        'desc' => 'patient_hist_count DESC',
                    ),
                    '*',
                ),
            ),
            'pagination' => array(
                'pageSize' => 15,
            ),
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Patients the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getSources(){
        $sources = Sources::model()->findAll(array('order' => 'title'));
       	$sources = CHtml::listData($sources,'id', 'title');

        return $sources;
    }

    public static function getSource($id){
        $info = Sources::model()->findByPk($id);
        return $info['title'];
    }

    public static function getStatuses(){
        $statuses = Status::model()->findAll(array('order' => 'status_id'));
        $statuses = CHtml::listData($statuses,'status_id', 'title');

        return $statuses;
    }

    public static function getStatus($id){
        $info = Status::model()->findByPk($id);
        return $info['title']."<br>";
    }

    public static function getAllStatuses($patient_id){
        $toothTips = array();
        $criteria=new CDbCriteria;
        $criteria->select='tooth_history_id, tooth_number, status_id';
        $criteria->condition = 'patient_id = "'.  (int)$patient_id .'"';

        $allStatuses = ToothHistory::model()->findAll($criteria);

        foreach ($allStatuses as $status){

            // Массив для подсказок
            if(array_key_exists($status['tooth_number'], $toothTips)){
                $toothTips[$status['tooth_number']] .= Patients::getStatus($status['status_id']);
            } else {
                $toothTips[$status['tooth_number']] = Patients::getStatus($status['status_id']);
            }
        }

        return json_encode($toothTips,JSON_FORCE_OBJECT);
    }


    protected function beforeDelete()
    {
        if ($this->id) {

			 // Удаляем историю лечений пациента
        	$criteria=new CDbCriteria;
			$criteria->condition = 'patient_id = "'.  (int)$this->id .'"';

        	$histories = PatientsHistory::model()->FindAll($criteria);
        	
        	if(count($histories) > 0){
        		foreach ($histories as $history) {
        			PatientsHistory::model()->findByPk($history->id)->delete();
        		}
        	}

        	 // Удаляем историю зубов пациента
			ToothHistory::model()->deleteAllByAttributes(array('patient_id'=>$this->id));


			 // Удаляем файлы пациента
			$full_path = 'upload/patients_files/'.$this->id.'/';

			if(is_dir($full_path)) {
				foreach (glob($full_path.'*') as $file)
				unlink($file);

			    rmdir($full_path); 
			}

			PatientsFiles::model()->deleteAllByAttributes(array('patient_id'=>$this->id));

        }
        
        return parent::beforeDelete();
    }


	public static function getSelectListPatientVisits($patient_id, $selected_id=0 ){

		$all_visits = self::getPatientVisits($patient_id);

		$list = CHtml::listData($all_visits, 'id', function($get_data){ return 'Визит ('.PatientsHistory::getPrettyDate($get_data->datetime_visit) . ") "; } );

		return CHtml::dropDownList('visit', (int)$selected_id,
	        $list,
	        array('empty' => 'Выберите визит')
	    );
	}

	public function getPatientVisits($patient_id){
		$patient_id = (int)$patient_id;

		if ($patient_id > 0 ) {
			$criteria2 = new CDbCriteria;
			$criteria2->condition = "patient_id=". $patient_id;
			$criteria2->order = "datetime_visit DESC";
			
			return PatientsHistory::model()->cache(1000)->findAll($criteria2);
		} else {
			return false;
		}
	}


}
