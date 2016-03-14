<?php

namespace indicalabs\select2;

use yii\helpers\Html;
use yii\jui\InputWidget;

/**
 * Wrapper Widget to use jQuery Select2 in Yii2 application.
 *
 * @author Venu Narukulla. Venu <venu.narukulla@gmail.com>
 * @copyright Copyright &copy; 2013 
 * @package extensions
 * @subpackage select2
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 1.0 rev.0
 * IN EXTENSIONS.PHP
 * 'yiisoft/yii2-select2' => 
 *	 array (
 *  	 'name' => 'indicalabs/yii2-select2',
 *   	 'version' => '9999999-dev',
 *   	 'alias' => 
 *   	  array (
 *     		'@yii/select2' => $vendorDir . '/indicalabs/yii2-select2/yii/select2',
 *  	 ),
 * 		),
 * <?= $form->field($model,'country_id')->widget(yii\select2\Select2::className(),[
 *					'model'=>$model,
 *					'attribute'=>'country_id',
 *					'language' => 'ru',
 *	//		'multiple' => false,  // multiple option is working only in case of non ajax
 *	// 		'data' => [
 *	//					1 => 'Option 1',
 *	//					2 => 'Option 2',
 *	//					3 => 'Option 3',
 *	//					4 => 'Option 4',
 *	//				],
 *	//	
 *		'clientOptions' => [
 *								 'allowClear' => true,
 *								 'placeholder' => 'Please Select a Value',
 *								 'width' => '100%',
 *									 'ajax' => [
 *									 'url' => Yii::$app->urlManager->createAbsoluteUrl('data/caste/findcountry'), 
 *									 'dataType' => 'json',
 *									 'data' => new yii\web\JsExpression('function (term, page) {
 *													return {
 *														term: term, // search term
 *														page: page,
 *														page_limit: 10,
 *													};
 *												}'),
 *									 'results' => new yii\web\JsExpression('function (data, page) { // parse the results into the format expected by Select2.
 *												return {
 *														results: data
 *														};
 *												}')			
 *												],  
 *							],	
 *					]) ?>
 * ```
 *
 * in your Ajax Controller
 * ```
 * class AjaxController extends Controller
 * {
 *      public function actionFindcountry($term)
 *	     {
 *		         $term = $_GET['term'];
 *		       //  echo "term---".$term;
 *		         $countries = Country::findBySql("select id, country_name as text from tbl_country where country_name LIKE :name",array(':name'=>$term.'%'))
 *		         ->asArray()
 *		         ->all();
 *		         echo json_encode($countries);
 *	     }
 *
 * } 
 * @see https://github.com/ivaynberg/select2 jQuery Select2
 */
class Select2Widget extends InputWidget
{

	public $language = false;
	
	/** @var bool Multiple or single item should be selected */
	public $multiple = false;

	/** @var array Array of items to list in the dropdown */
	public $data;
	
	public function init()
	{
		parent::init();
		
		$this->options['multiple'] = $this->multiple ? true : false;
	}

	/** Render widget html and register client scripts */
	public function run()
	{
		if ($this->language !== false) {
			$view = $this->getView();
			Select2RegionalAsset::register($view);
			
			/** Register language asstets in the above Select2RegionalAsset and delete the below
				$lang = strtoupper(str_replace('_', '-', Yii::$app->language));
				$lang[0] = strtolower($lang[0]);
				$lang[1] = strtolower($lang[1]);

				$cs->registerScriptFile($this->assetsDir . $src . '/select2_locale_' . $lang . $min . '.js');
			 */

			$this->registerWidget('select2', Select2Asset::className());
		} else {
			$this->registerWidget('select2', Select2Asset::className());
		}
		echo $this->renderWidget() . "\n";
	}

	/**
	 * Renders the AutoComplete widget.
	 * @return string the rendering result.
	 */
	public function renderWidget()
	{
		$contents = [];
		if (isset($this->clientOptions['ajax'])) {
					if (isset($this->model)) {
						$contents[] =  Html::activeTextInput($this->model, $this->attribute, $this->options);
					} else {
						$contents[] =  Html::textInput($this->name, $this->value, $this->options);
					}
				} else {
					if (isset($this->model)) {
						$contents[] =  Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
					} else {
						$contents[] =  Html::dropDownList($this->name, $this->value, $this->data, $this->options);
					}
				}
		return implode("\n", $contents);
	}

}

