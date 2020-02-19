<?php

    namespace App\Controllers;

    class AdminItemManagmentController extends \App\Core\role\AdminRoleController{
        
        public function items() {
            $itemModel = new \App\Models\ItemModel($this->getDatabaseConnection());
            $items = $itemModel->getAllVisible();
            $this->set('items', $items);
        }

        public function getEdit($itemId){
            $itemModel = new \App\Models\ItemModel($this->getDatabaseConnection());
            $item = $itemModel -> getById($itemId);

            if(!$item){
                $this->redirect('/admin/items');
            }

            $this->set('item', $item);

            return  $itemModel;

        }

        public function postEdit($itemId){

            $itemModel = $this->getEdit($itemId);

            $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $input_price = \filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
            $price = (float)$input_price;
            

            $itemModel->editById($itemId, [
                'name' => $name,
                'description' => $description,
                'price' => $price
            ]);

            $this->redirect('/admin/items');

        }

        public function getAdd(){}

        public function postAdd(){
            $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $input_price = \filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
            $price = (float)$input_price;
            $categoryId = \filter_input(INPUT_POST, 'catId', FILTER_SANITIZE_NUMBER_INT);

            $itemModel = new \App\Models\ItemModel($this->getDatabaseConnection());

            $itemId = $itemModel->add([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'admin_id' => $this->getSession()->get('admin_id'),
                'category_id' => $categoryId
            ]);

            if($itemId){
                $this->redirect('/admin/items');
            }

            $this->set('message', 'Error!');
        }

        public function getDelete($itemId){
            $itemModel = $this->getEdit($itemId);
            #print_r($categoryId);

            $itemModel->deleteItemById($itemId);

            $this->redirect('/admin/items');

        }

    }