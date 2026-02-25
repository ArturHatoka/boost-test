<?php

namespace app\models;

use yii\db\ActiveRecord;

class Client extends ActiveRecord
{
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';

    public static function tableName(): string
    {
        return '{{%client}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'state'], 'required'],
            [['name', 'state', 'status'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['state'], 'string', 'max' => 128],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    public function fields(): array
    {
        return ['id', 'name', 'state', 'status'];
    }
}
