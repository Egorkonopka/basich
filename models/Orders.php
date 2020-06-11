<?php 
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


class Orders extends ActiveRecord{

	public static function tableName(){
		return 'orders';
	}

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }


	public function getOrdersItems(){
		return $this->hasMany(OrdersItems::className(),['order_id' => 'id']);
	}


	public function rules()
    {
        return [
            [['name', 'email', 'phone', 'address'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['qty'], 'integer'],
            [['sum'], 'number'],
            [['status'], 'boolean'],
            [['name', 'email', 'phone', 'address'], 'string', 'max' => 100],
        ];
    }


	public function attributeLabels() {
		return [
		'name' => 'Имя',
		'email' => 'e-mail',
		'phone' => 'Телефон',
		'address' => 'Адрес',
	];
	}




}


?>