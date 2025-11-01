<?php

namespace app\controllers\api;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\web\Response;
use app\models\Booking;

/**
 * Booking API Controller
 */
class BookingController extends ActiveController
{
    public $modelClass = 'app\models\Booking';

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

        // Expand relations in index and view
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new \yii\base\DynamicModel(['email']);
        $searchModel->addRule(['email'], 'string');

        $query = Booking::find()->with(['customer', 'service']);

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
    }

    /**
     * Check available time slots for a specific date
     */
    public function actionAvailableSlots($date)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Generate time slots from 9:00 to 18:00
        $slots = [];
        $startHour = 9;
        $endHour = 18;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $time = sprintf('%02d:00:00', $hour);

            // Check if this slot is already booked
            $isBooked = Booking::find()
                ->where(['booking_date' => $date, 'booking_time' => $time])
                ->andWhere(['!=', 'status', Booking::STATUS_CANCELLED])
                ->exists();

            $slots[] = [
                'time' => $time,
                'available' => !$isBooked,
            ];
        }

        return [
            'date' => $date,
            'slots' => $slots,
        ];
    }
}
