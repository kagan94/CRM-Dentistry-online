<?php

class FastAddingPatient extends CFormModel
{
	public $patient_fio;
	public $patient_id;
	public $phone;
	public $date_birthday;
    public $datetime_visit;
    public $doctor_id;
    public $affiliate_id;
    public $work_place_id;
    public $comment;
    public $new_patient;

	public function rules()
	{
		return array(
			array('patient_fio, datetime_visit, doctor_id, affiliate_id, work_place_id,  ', 'required'),
			array('comment', 'length', 'max'=>25),
			array('phone', 'numerical', 'integerOnly'=>true),
			array('patient_fio, patient_id, phone, date_birthday, datetime_visit, doctor_id, affiliate_id, work_place_id, comment, new_patient', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(			
			'patient_id' => 'Ф.И.О. пациента',
			'patient_fio' => 'Ф.И.О. пациента',
			'phone' => 'Телефон',
			'date_birthday' => 'Дата Рождения',
            'datetime_visit' => 'Дата и время визита',
            'doctor_id' => 'Лечащий врач',
            'affiliate_id' => 'Филиал',
            'work_place_id' => 'Кресло',
            'comment' => 'Комментарий к записи',
            'new_patient' => 'Новый пациент?',
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
