<?php

namespace app\controllers\api;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

/**
 * Customer API Controller
 */
class CustomerController extends ActiveController
{
    public $modelClass = 'app\models\Customer';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // Customize actions if needed
        return $actions;
    }
}
