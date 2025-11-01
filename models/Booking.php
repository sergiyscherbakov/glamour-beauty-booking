<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Booking model
 *
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * @property string $booking_date
 * @property string $booking_time
 * @property string $status
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Customer $customer
 * @property Service $service
 */
class Booking extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bookings}}';
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
            [['user_id', 'service_id', 'booking_date', 'booking_time'], 'required'],
            [['user_id', 'service_id'], 'integer'],
            [['booking_date', 'booking_time'], 'safe'],
            [['notes'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED,
                self::STATUS_CANCELLED,
                self::STATUS_COMPLETED
            ]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['user_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::class, 'targetAttribute' => ['service_id' => 'id']],
            // Unique validation: cannot book the same service at the same time and date
            [['service_id'], 'validateUniqueBooking'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'service_id' => 'Service',
            'booking_date' => 'Date',
            'booking_time' => 'Time',
            'status' => 'Status',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    /**
     * Get available statuses
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    /**
     * Validate that the same service cannot be booked at the same date and time
     */
    public function validateUniqueBooking($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $query = self::find()
                ->where([
                    'service_id' => $this->service_id,
                    'booking_date' => $this->booking_date,
                    'booking_time' => $this->booking_time,
                ])
                ->andWhere(['!=', 'status', self::STATUS_CANCELLED]);

            // Exclude current record when updating
            if (!$this->isNewRecord) {
                $query->andWhere(['!=', 'id', $this->id]);
            }

            if ($query->exists()) {
                $this->addError($attribute, 'Ця послуга вже заброньована на цей час. Будь ласка, оберіть інший час.');
            }
        }
    }

    /**
     * Fields to export in API
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['customer'] = 'customer';
        $fields['service'] = 'service';
        return $fields;
    }
}
