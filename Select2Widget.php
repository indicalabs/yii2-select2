<?php

namespace indicalabs\select2;

use yii\helpers\Html;
use yii\helpers\Url;
use conquer\helpers\Json;

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
class Select2Widget extends \yii\widgets\InputWidget
{


	/**
	 * Points to use Bootstrap theme
	 * @var boolean
	 */
	public $bootstrap = true;
	/**
	 * Language code
	 * Set False to disable
	 * @var string | boolean
	 */
	public $language;
	/**
	 * Array data
	 * @example [['id'=>1, 'text'=>'enhancement'], ['id'=>2, 'text'=>'bug']]
	 * @var array
	 */
	public $data;
	/**
	 * You can use Select2Action to provide AJAX data
	 * @see \yii\helpers\BaseUrl::to()
	 * @var array|string
	 */
	public $ajax;
	/**
	 * @see \yii\helpers\BaseArrayHelper::map()
	 * @var array
	 */
	public $items = [];
	/**
	 * A placeholder value can be defined and will be displayed until a selection is made
	 * @var string
	 */
	public $placeholder;
	/**
	 * Multiple select boxes
	 * @var boolean
	 */
	public $multiple;
	/**
	 * Tagging support
	 * @var boolean
	 */
	public $tags;
	/**
	 * @link https://select2.github.io/options.html
	 * @var array
	 */
	public $settings = [];
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
	
		if ($this->tags) {
			$this->options['data-tags'] = 'true';
			$this->options['multiple'] = true;
		}
		if ($this->language) {
			$this->options['data-language'] = $this->language;
		}
		if (!is_null($this->ajax)) {
			$this->options['data-ajax--url'] = Url::to($this->ajax);
			$this->options['data-ajax--cache'] = 'true';
		}
		if ($this->placeholder) {
			$this->options['data-placeholder'] = $this->placeholder;
		}
		if ($this->multiple) {
			$this->options['data-multiple'] = 'true';
			$this->options['multiple'] = true;
		}
		if (!empty($this->data)) {
			$this->options['data-data'] = \yii\helpers\Json::encode($this->data);
		}
		if (!isset($this->options['class'])) {
			$this->options['class'] = 'form-control';
		}
		if ($this->bootstrap) {
			$this->options['data-theme'] = 'bootstrap';
		}
		if ($this->multiple || !empty($this->settings['multiple'])) {
			if ($this->hasModel()) {
				$name = isset($this->options['name']) ? $this->options['name'] : Html::getInputName($this->model, $this->attribute);
			} else {
				$name = $this->name;
			}
			if (substr($name, -2) != '[]') {
				$this->options['name'] = $this->name = $name . '[]';
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->hasModel()) {
			echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
		} else {
			echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
		}
		$this->registerAssets();
	}
	
	/**
	 * Registers Assets
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		$bandle = Select2Asset::register($view);
		if ($this->language !== false) {
			$langs[0] = $this->language ? $this->language : \Yii::$app->language;
			if (($pos = strpos($langs[0], '-')) > 0) {
				// If "en-us" is not found, try to use "en".
				$langs[1] = substr($langs[0], 0, $pos);
			}
			foreach ($langs as $lang) {
				$langFile = "/js/i18n/{$lang}.js";
				if (file_exists($bandle->sourcePath . $langFile)) {
					$view->registerJsFile($bandle->baseUrl . $langFile, ['depends' => Select2Asset::className()]);
					break;
				}
			}
		}
		if ($this->bootstrap) {
			Select2BootstrapAsset::register($view);
		}
		$settings = Json::encode($this->settings);
		$view->registerJs("jQuery('#{$this->options['id']}').select2($settings);");
	}

	/**
	 * Renders the AutoComplete widget.
	 * @return string the rendering result.
	 
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
*/
}

