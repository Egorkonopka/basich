<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;



class OrdersItems extends ActiveRecord{

	public static function tableName(){
		return 'orders_items';
	}


	public function getOrders(){
		return $this->hasOne(Orders::className(),['id' => 'order_id']);
	}

	public function rules(){
		return [
			[['order_id', 'product_id', 'name', 'price', 'qty_item','sum_item'], 'required' ],
			[['order_id', 'product_id','qty_item'], 'integer'],
			[['price','sum_item'], 'number'],
			[['name'], 'string', 'max'=>100 ],

		];
	}

}

?>