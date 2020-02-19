<?php
    namespace App\Controllers;

use LengthException;

class MainController extends \App\Core\Controller {

        public function home() {
            $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAllVisible();
            $this->set('categories', $categories);

            /*
            //$this->getSession()->put('neki_kljuc', 'Neka vrednost' . rand(100, 999));
            
            $staraVrednost = $this->getSession()->get('neki_kljuc', '/');
            $this->set('podatak', $staraVrednost);
        
            //$this->getSession()->clear();
            */

            $staraVrednost = $this->getSession()->get('brojac', 0);
            $novaVrednost = $staraVrednost + 1;
            $this->getSession()->put('brojac', $novaVrednost);

            $this->set('podatak', $novaVrednost);

            $this->sendMessagesToView();

        }

        public function getRegister() {}

        public function postRegister(){
            $email = \filter_input(INPUT_POST, 'reg_email', FILTER_SANITIZE_EMAIL);
            $forename = \filter_input(INPUT_POST, 'reg_forename', FILTER_SANITIZE_STRING);
            $surname = \filter_input(INPUT_POST, 'reg_surname', FILTER_SANITIZE_STRING);
            $username = \filter_input(INPUT_POST, 'reg_username', FILTER_SANITIZE_STRING);
            $password1 = \filter_input(INPUT_POST, 'reg_password_1', FILTER_SANITIZE_STRING);
            $password2 = \filter_input(INPUT_POST, 'reg_password_2', FILTER_SANITIZE_STRING);

            if($password1 !== $password2){
                $this->set('message','Error passwords do not match!');
                return;
            }

            if(strlen($password1) < 6){
                $this->set('message','Error passwords must be at least 6 characters long!');
                return;
            }

            $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
            
            $user = $userModel->getByFieldName('email', $email);
            if($user){
                $this->set('message', 'Error user already exists');
                return;
            }

            $user = $userModel->getByFieldName('username', $username);
            if($user){
                $this->set('message', 'Error username already exists');
                return;
            }

            $passwordHash = \password_hash($password1, PASSWORD_DEFAULT);

            $userId = $userModel->add([
                'forename' => $forename,
                'surname' => $surname,
                'username' => $username,
                'password_hash' => $passwordHash,
                'email' => $email
            ]);

            if(!$userId){
                $this->set('message', 'Error');
            }

            $this->redirect('/');
        }

        
        public function getLogin() {}

        public function postLogin(){
            $username = \filter_input(INPUT_POST, 'login_username', FILTER_SANITIZE_STRING);
            $password = \filter_input(INPUT_POST, 'login_password', FILTER_SANITIZE_STRING);

            // TODO: verifikacija password-a

            
            $userModel = new \App\Models\UserModel($this->getDatabaseConnection());
            
            $user = $userModel->getByFieldName('username', $username);
            if(!$user){
                $this->set('message', 'Error user doesnt exist');
                return;
            }

            if(!password_verify($password, $user->password_hash)){
                sleep(1);
                $this->set('message', 'Password not valid');
                return;
            }

           $this->getSession()->put('user_id', $user->user_id);

            $cartModel = new \App\Models\CartModel($this->getDatabaseConnection());
            $cartData = $cartModel->getLatestUnusedCart($user->user_id);
            if(!$cartData){
               $cartId =  $cartModel -> add (['user_id' => $user->user_id]);

            } else {
                $cartId = $cartData -> cart_id;
            }

            $this ->getSession()->put('cart_id', $cartId);

            $this ->getSession()->put('messages', []);


           $this->getSession()->save();

           $this->redirect('/');

        }

        public function getAdminLogin(){}

        public function postAdminLogin(){
            $username = \filter_input(INPUT_POST, 'login_username', FILTER_SANITIZE_STRING);
            $password = \filter_input(INPUT_POST, 'login_password', FILTER_SANITIZE_STRING);

            $adminModel = new \App\Models\AdminModel($this->getDatabaseConnection());
            $admin = $adminModel -> getByFieldName('username', $username);

            if(!$admin){
                $this->set('message', 'Error admin doesnt exist');
                return;
            }

            #$passwordHash = \password_hash($password, PASSWORD_DEFAULT);

            if(!password_verify($password, $admin->password_hash)){
                sleep(1);
                $this->set('message', 'Password not valid');
                return;
            }

            $this->getSession()->put('admin_id', $admin->admin_id);
            $this->getSession()->save();
 
            $this->redirect('/');


        }

        public function getLogout() {
            $this->getSession()->clear();
            $this->getSession()->save();

            $this->redirect('/user/login');

        }

        public function getAdminLogout() {
            $this->getSession()->clear();
            $this->getSession()->save();

            $this->redirect('/admin/login');

        }

    }


?>