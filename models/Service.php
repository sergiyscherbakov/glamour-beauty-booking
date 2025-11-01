<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Service model
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $category
 * @property string|null $image_url
 * @property int $duration Duration in minutes
 * @property float $price
 * @property int $is_active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Booking[] $bookings
 */
class Service extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%services}}';
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
            [['name', 'duration', 'price'], 'required'],
            [['description'], 'string'],
            [['duration', 'is_active'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['category'], 'string', 'max' => 50],
            [['image_url'], 'string', 'max' => 500],
            [['is_active'], 'default', 'value' => 1],
            [['is_active'], 'in', 'range' => [0, 1]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'category' => 'Category',
            'image_url' => 'Image URL',
            'duration' => 'Duration (minutes)',
            'price' => 'Price',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['service_id' => 'id']);
    }

    /**
     * Scope to get only active services
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findActive()
    {
        return static::find()->where(['is_active' => 1]);
    }
}
