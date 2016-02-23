<?php
class RequestController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly -uploadFile'
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array(
					'suggestCountry','legacySuggestCountry','fillTree','treePath','loadContent','suggestAuPlaces',
					'suggestAuHierarchy','suggestLastname','fillAuTree','viewUnitPath','viewUnitLabel','initPerson',
					'suggestPerson','suggestPersonGroupCountry','listPersonsWithSameFirstname',
					'addTabularInputs','addTabularInputsAsTable','uploadFile'
				),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * @return array actions
	 */
	public function actions()
	{
		return array(
			'suggestCountry'=>array(
				'class'=>'ext.actions.XSuggestAction',
				'modelName'=>'Country',
				'methodName'=>'suggest',
			),
			'legacySuggestCountry'=>array(
				'class'=>'ext.actions.XLegacySuggestAction',
				'modelName'=>'Country',
				'methodName'=>'legacySuggest',
			),
			'fillTree'=>array(
				'class'=>'ext.actions.XFillTreeAction',
				'modelName'=>'Menu',
				'showRoot'=>false
			),
			'treePath'=>array(
				'class'=>'ext.actions.XAjaxEchoAction',
				'modelName'=>'Menu',
				'attributeName'=>'pathText',
			),
			'uploadFile'=>array(
				'class'=>'ext.actions.XHEditorUpload',
			),
			'suggestAuPlaces'=>array(
				'class'=>'ext.actions.XSuggestAction',
				'modelName'=>'AdminUnit',
				'methodName'=>'suggestPlaces',
				'limit'=>30
			),
			'suggestAuHierarchy'=>array(
				'class'=>'ext.actions.XSuggestAction',
				'modelName'=>'AdminUnit',
				'methodName'=>'suggestHierarchy',
				'limit'=>30
			),
			'suggestLastname'=>array(
				'class'=>'ext.actions.XSuggestAction',
				'modelName'=>'PatientsHistory',
				'methodName'=>'suggestLastname',
				'limit'=>30
			),
			'fillAuTree'=>array(
				'class'=>'ext.actions.XFillTreeAction',
				'modelName'=>'AdminUnit',
				'showRoot'=>false,
			),
			'viewUnitPath'=>array(
				'class'=>'ext.actions.XAjaxEchoAction',
				'modelName'=>'AdminUnit',
				'attributeName'=>'rootlessPath',
			),
			'viewUnitLabel'=>array(
				'class'=>'ext.actions.XAjaxEchoAction',
				'modelName'=>'AdminUnit',
				'attributeName'=>'label',
			),
			'initPerson'=>array(
				'class'=>'ext.actions.XSelect2InitAction',
				'modelName'=>'PatientsHistory',
				'textField'=>'fullname',
			),
			'suggestPerson'=>array(
				'class'=>'ext.actions.XSelect2SuggestAction',
				'modelName'=>'PatientsHistory',
				'methodName'=>'suggestPerson',
				'limit'=>30
			),
			'suggestPersonGroupCountry'=>array(
				'class'=>'ext.actions.XSelect2SuggestAction',
				'modelName'=>'PatientsHistory',
				'methodName'=>'suggestPersonGroupCountry',
				'limit'=>30
			),
			'addTabularInputs'=>array(
				'class'=>'ext.actions.XTabularInputAction',
				'modelName'=>'PatientsHistory',
				'viewName'=>'/PatientsHistory/_tabularInput',
			),
			'addTabularInputsAsTable'=>array(
				'class'=>'ext.actions.XTabularInputAction',
				'modelName'=>'PatientsHistory',
				'viewName'=>'/PatientsHistory/_tabularInputAsTable',
			),
		);
	}

	/**
	 * Displays list on persons that have same firstname as person with given id
	 */
}