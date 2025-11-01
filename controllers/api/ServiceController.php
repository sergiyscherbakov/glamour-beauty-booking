<?php

namespace app\controllers\api;

use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\data\ActiveDataProvider;
use app\models\Service;

/**
 * Service API Controller
 */
class ServiceController extends ActiveController
{
    public $modelClass = 'app\models\Service';

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

        // Override the index action to show only active services
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * Prepare data provider for index action
     */
    public function prepareDataProvider()
    {
        return new ActiveDataProvider([
            'query' => Service::find()->where(['is_active' => 1]),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
    }
}
