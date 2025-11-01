<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Admin model
 *
 * @property int $id
 * @property string $login
 * @property string $password_hash
 * @property string|null $name
 * @property int $created_at
 * @property int $updated_at
 */
class Admin extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admins}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password_hash'], 'required'],
            [['login'], 'string', 'max' => 100],
            [['password_hash'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 100],
            [['login'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password_hash' => 'Password Hash',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current admin
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * Finds admin by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * Sets password hash
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
}
