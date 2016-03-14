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
	public $sourcePath = '@bower/select2/dist';
	public $js = [
			'js/select2.full.min.js',
	];
	
	public $css = [
			'css/select2.min.css',
	];
	public $depends = [
			'yii\web\JqueryAsset',
	];
}
