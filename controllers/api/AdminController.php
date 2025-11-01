<?php

namespace app\controllers\api;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use app\models\Admin;
use app\models\Booking;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:8080'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'login' => ['POST'],
                'logout' => ['POST'],
                'bookings' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Login action
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;

        // Get JSON body data
        $bodyParams = $request->getBodyParams();
        $login = $bodyParams['login'] ?? $request->post('login');
        $password = $bodyParams['password'] ?? $request->post('password');

        if (!$login || !$password) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Login and password are required'];
        }

        $admin = Admin::findByLogin($login);

        if (!$admin || !$admin->validatePassword($password)) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Invalid login or password'];
        }

        // Create session
        $session = Yii::$app->session;
        $session->set('admin_id', $admin->id);
        $session->set('admin_login', $admin->login);

        return [
            'success' => true,
            'admin' => [
                'id' => $admin->id,
                'login' => $admin->login,
                'name' => $admin->name,
            ],
        ];
    }

    /**
     * Logout action
     */
    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->remove('admin_id');
        $session->remove('admin_login');
        $session->destroy();

        return ['success' => true];
    }

    /**
     * Check if admin is logged in
     */
    public function actionCheck()
    {
        $session = Yii::$app->session;
        $adminId = $session->get('admin_id');

        if (!$adminId) {
            Yii::$app->response->statusCode = 401;
            return ['authenticated' => false];
        }

        $admin = Admin::findOne($adminId);

        if (!$admin) {
            Yii::$app->response->statusCode = 401;
            return ['authenticated' => false];
        }

        return [
            'authenticated' => true,
            'admin' => [
                'id' => $admin->id,
                'login' => $admin->login,
                'name' => $admin->name,
            ],
        ];
    }

    /**
     * Get all bookings (admin only)
     */
    public function actionBookings()
    {
        $session = Yii::$app->session;
        $adminId = $session->get('admin_id');

        if (!$adminId) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Unauthorized'];
        }

        $bookings = Booking::find()
            ->with(['customer', 'service'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $bookings;
    }
}
