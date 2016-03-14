<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace indicalabs\select2;

/**
 * @author Venu Narukulla. Venu <venu.narukulla@gmail.com>
 * 
 * @since 2.0
 */
// @ TODO This file is to load language options 
class Select2RegionalAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@bower/select2/dist';

	public $js = [
		//'jquery.ui.datepicker-i18n.js',
	];
	public $depends = [
		'indicalabs\select2\Select2Asset',
	];
}
