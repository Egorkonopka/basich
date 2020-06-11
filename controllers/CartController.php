<?php

namespace app\controllers;
use app\models\Product;
use app\models\Cart;
use app\models\Orders;
use app\models\OrdersItems;
use Yii;


class CartController extends AppController {

	public function actionAdd(){
		$id = Yii::$app->request->get('id');
		$qty = (int)Yii::$app->request->get('qty');
		$qty = !$qty ? 1 : $qty;
		$product = Product::findOne($id);
		if(empty($product)) return false;
		$session = Yii::$app->session;
		$session ->open();
		$cart = new Cart();
		$cart->addToCart($product,$qty);
		$this->layout = false;
		return $this->render('cart-modal',compact('session'));
		// debug($session['cart']);
	}

    public function actionClear(){
        $session =Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }

    public function actionDelItem(){
        $id = Yii::$app->request->get('id');
        $session =Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalc($id);
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }

    public function actionShow(){
        $session =Yii::$app->session;
        $session->open();
        $this->layout = false;
        return $this->render('cart-modal', compact('session'));
    }


    public function actionView(){
    	$session =Yii::$app->session;
        $session->open();
        $this->setMeta('Корзина');
        $orders = new Orders();
        if( $orders->load(Yii::$app->request->post()) ){
        	$orders->qty = $session['cart.qty'];
            $orders->sum = $session['cart.sum'];
            if($orders->save()){
            	$this->saveOrdersItems($session['cart'], $orders->id);
            	Yii::$app->session->setFlash('success','Ваш заказ принят');
            	return $this->refresh();
            }else{
            	Yii::$app->session->setFlash('error','Ошибка оформления заказа');
            }
        }
        // debug($orders);

        return $this->render('view',compact('session','orders'));
    }


        protected function saveOrdersItems($items, $order_id){
        foreach($items as $id => $item){
            $orders_items = new OrdersItems();
            $orders_items->order_id = $order_id;
            $orders_items->product_id = $id;
            $orders_items->name = $item['name'];
            $orders_items->price = $item['price'];
            $orders_items->qty_item = $item['qty'];
            $orders_items->sum_item = $item['qty'] * $item['price'];
            $orders_items->save();
        }
        // debug($orders_items);
    }

}
