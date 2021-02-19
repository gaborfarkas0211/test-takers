<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property string $testTaker
 * @property int|null $correctAnswers
 * @property int|null $incorrectAnswers
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['testTaker', 'correctAnswers', 'incorrectAnswers'], 'required'],
            [['correctAnswers', 'incorrectAnswers'], 'integer'],
            [['testTaker'], 'string', 'max' => 255],
            ['testTaker', 'match', 'pattern' => '/^([0-9]{2})-([0-9]{3})-([0-9]{4})/'],
            ['testTaker', 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testTaker' => 'Test Taker',
            'correctAnswers' => 'Correct Answers',
            'incorrectAnswers' => 'Incorrect Answers',
        ];
    }
}
