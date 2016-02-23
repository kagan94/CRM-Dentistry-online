<?php

/**
 * This is the model class for table "discounts".
 *
 * The followings are the available columns in table 'discounts':
 * @property integer $discount_id
 * @property string $title
 * @property integer $type
 * @property double $size
 * @property string $date_added
 * @property string $date_modifidied
 *
 * The followings are the available model relations:
 * @property DiscountHistory[] $discountHistories
 */
class Discounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'discounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, type, size', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('size', 'numerical'),
			array('title', 'length', 'max'=>150),
			array('date_added, date_modifidied', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('discount_id, title, type, size, date_added, date_modifidied', 'safe', 'on'=>'search'),
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
			'discountHistories' => array(self::HAS_MANY, 'DiscountHistory', 'discount_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'type' => 'Тип скидки',
			'size' => 'Размер скидки',
			'type-size' => 'Размер скидки и ее тип',
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

		$criteria->compare('discount_id',$this->discount_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('size',$this->size);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('date_modifidied',$this->date_modifidied,true);

        $sort = new CSort();
        $sort->defaultOrder = 't.type DESC, t.size ASC';

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
	 * @return Discounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
