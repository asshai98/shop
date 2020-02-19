<?php

    namespace App\Controllers;

    class CartController extends \App\Core\Controller {
        public function getAdd($itemId) {
            $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());

            $cartModel->addItem($this->getSession()->get('cart_id'), $itemId);

            $this->addMessage("Successfuly added to cart!");
            #print_r($this->getSession()->get('messages'));
            #exit;

            $this->redirect('/item/' . $itemId);
        }

        public function viewCart() {
            $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());

            $content = $cartModel->getCartContent($this->getSession()->get('cart_id'));
            $this->set('content', $content);

        }

        public function deleteItem($itemId){
            $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());
            $cartModel->deleteOneItem($itemId, $this->getSession()->get('cart_id'));
            $this->addMessage('One item was deleted!');
            $this->redirect('/user/cart');
        }

        public function numberOfItems(){
            $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());

            $counter = $cartModel->itemCounter($this->getSession()->get('cart_id'));
            $this->set('counter', $counter);
        }

        public function postOrder(){
            $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $address = \filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

            $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());
            $orderId = $cartModel->createOrder($this->getSession()->get('cart_id'), $name, $address);

            if($orderId){
                $this->addMessage('Your order was confirmed!');
                $cartId = $cartModel->add(['user_id' => $this->getSession()->get('user_id')]);
                $this -> getSession()->put('cart_id', $cartId);
                $this->addMessage('New cart has been open');
                
            } else {
                $this->addMessage('Erro! Your order was not send! Try again!');
            }

            $this->redirect("/");

            







        }

    }