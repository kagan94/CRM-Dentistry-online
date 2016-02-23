<?php

/**
 * This is the model class for table "services".
 *
 * The followings are the available columns in table 'services':
 * @property integer $service_id
 * @property string $title
 * @property string $code
 * @property integer $price
 * @property string $date_added
 * @property string $date_modifidied
 *
 * The followings are the available model relations:
 * @property OrderServices[] $orderServices
 */
class Services extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'services';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('price, parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>150),
			array('code', 'numerical', 'allowEmpty' => true, 'integerOnly' => false),
			array('date_added, date_modifidied', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('service_id, title, code, price, date_added, date_modifidied, parent_id', 'safe', 'on'=>'search'),
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
			'orderServices' => array(self::HAS_MANY, 'OrderServices', 'service_id'),
		    'parent' => array(self::BELONGS_TO, 'Services', 'parent_id'),
            'children' => array(self::HAS_MANY, 'Services', 'parent_id',
                'order'=>'children.code ASC'
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'service_id' => 'ID',
            'title' => 'Название',
            'code' => 'Код',
            'date_added' => 'Дата добавления',
            'date_modifidied' => 'Дата последнего изменения',
            'price' => 'Цена',
            'price_uah' => 'Цена, грн.',
            'parent_id' => 'Родительская категория',
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

		$criteria->compare('service_id',$this->service_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('date_added',$this->date_added,true);
		$criteria->compare('date_modifidied',$this->date_modifidied,true);
		$criteria->compare('parent_id',$this->parent_id,true);

        return new DTreeActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
               'pageSize'=>100,
            ),
            'childRelation'=>'children',
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Services the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function buildTree(array $elements, $parentId = 0) {
	
	    $branch = array();
	 
	    foreach ($elements as $element) {

	        if ($element['parent_id'] == $parentId) {

	            $children = Services::model()->buildTree($elements, $element['service_id']);

	            if ($children) {
	                $element['children'] =  $children;
	            }

	            $branch[] = $element;
	        }
	    }
	 
	    return $branch;
	}


	public static function getTreeServices($current_service_id = '', $number_row = 0){ 
   		
	    $info = Services::model()->FindAll(array('order' => 'code')); // array('order' => 'code')
	    $services = array();
   		$text = '';

		foreach ($info as $service) {
			$services[] = array(
				'service_id' => $service->service_id,
				'parent_id' => $service->parent_id,
				'title' => $service->title,
				'price' =>$service->price,
				'code' =>$service->code,
			);
		}

		$treeServices = Services::model()->buildTree($services);

		$text .= '
		<select data-toggle="tooltip" title="232" style="float: right; width:330px;" class="services checkSimilar" name="PatientsHistory[services][service_id][row_id_'.$number_row.']" id="service_id_'.$number_row.'">
		    <option value="0">Выберите услугу</option>';
		    
		    foreach ($treeServices as $service_info){ 
				
				if(!isset($service_info['children'])):
					$text .= '<optgroup label="'.$service_info['title'].'"></optgroup>';
				else:
					$text .= '<optgroup label="'.$service_info['title'].'">';
		    		
		    		foreach ($service_info['children'] as $service_childs_info) {
		    			 
		    			 $text .= '<option data-price="' . $service_childs_info['price'] . '" value="' . $service_childs_info['service_id'] . '"';
		    			 
		    			 if($service_childs_info['service_id'] == $current_service_id) {
		    			 	$text .= "selected >"; 
		    			 } else { 
		    			 	$text .= ">"; 
		    			 }

		    			 $text .= $service_childs_info['title'] . " - " . $service_childs_info['price'] . " грн.";
		    			 $text .= '</option>';
		    		}
					
					$text .= '</optgroup>';
		        endif;
		    }

		$text .= '</select>';

		return $text;
	}

}
