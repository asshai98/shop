<?php

    namespace App\Controllers;

    class AdminCategoryManagmentController extends \App\Core\role\AdminRoleController{
        
        public function categories() {
            $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAllVisible();
            $this->set('categories', $categories);

            $this->sendMessagesToView();
        }

        public function getEdit($categoryId){
            $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
            $category = $categoryModel->getById($categoryId);

            if(!$category){
                $this->redirect('/admin/categories');
            }

            $this->set('category', $category);

            return  $categoryModel;

        }

        public function postEdit($categoryId){

            $categoryModel = $this->getEdit($categoryId);

            $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

            $categoryModel->editById($categoryId, [
                'name' => $name,
                'cat_desc' => $description
            ]);

            $this->addMessage('Uspesno izmenjena kategorija');

            $this->redirect('/admin/categories');

        }

        public function getAdd(){}

        public function postAdd(){
            $name = \filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

            $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());


            $categoryId = $categoryModel->add([
                'name' => $name,
                'cat_desc' => $description,
                'admin_id' => $this->getSession()->get('admin_id')
            ]);



            if($categoryId){
                $this->redirect('/admin/categories');
            }

            $this->set('message', 'Error!');

        }

        
        public function getDelete($categoryId){
            $categoryModel = $this->getEdit($categoryId);
            #print_r($categoryId);

            $categoryModel->deleteById($categoryId);

            $this->redirect('/admin/categories');

        }
        
    }