<?php
/**
 * @link 
 * @copyright Copyright (c) 2016 Venu Narukulla
 * @license http://www.yiiframework.com/license/
 */

namespace indicalabs\select2;

/**
 * @author Venu Narukulla. Venu <venu.narukulla@gmail.com>
 * @since 2.0
 */
class Select2Asset extends \yii\web\AssetBundle
{
	/**
	 * @var string Plugin language
	 */
	public $language;
	
	public $sourcePath = '@bower/select2/dist';
	public $js = [
 			'js/select2.full.min.js',
//			'js/select2.min.js',
	];
	
	public $css = [
			'css/select2.min.css',
	];
	public $depends = [
			'yii\web\JqueryAsset',
	];
	
	/**
	 * @inheritdoc
	 */
	public function registerAssetFiles($view)
	{
		if ($this->language !== null) {
			$this->js[] = 'select2_locale_' . $this->language . '.js';
		}
		
		$view->registerJs(<<<JS
			function formatResult(item) {
				      if(!item.id) {
				        // return `text` for optgroup
				        return item.text;
				      }
				      // return item template
				      return '<i>' + item.text + '</i>';
				    };
		
		    function formatSelection(repo) {
					//repo.selected =true;
		      // return selection template
		      //return '<b>' + item.text + '</b>';
		        return repo.full_name || repo.text;
    		};
JS
	,			\yii\web\View::POS_READY);
		parent::registerAssetFiles($view);
	}
}
