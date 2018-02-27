<?php

namespace app\modules\owner;

/**
 * owner module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\owner\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'owner\default\index';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
