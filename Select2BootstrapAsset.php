<?php
/**
 * @link https://github.com/indicalabs/yii2-select2
 * @copyright Copyright (c) 2016 Venu Narukulla
 * @license https://github.com/indicalabs/yii2-select2/blob/master/LICENSE
 */

namespace indicalabs\select2;

/**
 * @author 
 * @link http://select2.github.io/select2-bootstrap-theme/
 */
class Select2BootstrapAsset extends \yii\web\AssetBundle
{
    // The files are not web directory accessible, therefore we need
	// to specify the sourcePath property. Notice the @npm alias used.
    public $sourcePath = '@npm/select2-bootstrap-theme/dist';
    
    public $css = [
        'select2-bootstrap.min.css',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'indicalabs\select2\Select2Asset',
    ];
}