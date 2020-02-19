<?php

    namespace App\Core;

    class Controller {
        private $dbc;
        private $session;
        private $data = []; // podaci koje prosledjujemo
        private $breadcrums = [];
        
        protected function addBreadcrum($link, $name){
            $this->breadcrums[]=(object)[
                "link"=>$link,
                "label"=>$name
            ];
        }

        public function getBreadcrums(){
            return $this->breadcrums;
        }

        public function __pre() {
            $this->set('role', 'VISITOR');
            
            if($this->getSession()->get('user_id')){
                $this->set('role', 'USER');
            }

            if($this->getSession()->get('admin_id')){
                $this->set('role', 'ADMIN');
            }
        }
        
        public function __post() {
            if($this->getSession()->get('user_id')){
                $cartModel =  new \App\Models\CartModel($this->getDatabaseConnection());
                $broj = $cartModel -> itemCounter($this->getSession()->get('cart_id'));
                $this->set('cartItemCount', $broj);
            }
        }

        final public function __construct(\App\Core\DatabaseConnection &$dbc) {
            $this->dbc = $dbc;
        }

        final public function &getSession(): \App\Core\Session\Session {
            return $this->session;
        }

        final public function setSession(\App\Core\Session\Session &$session) {
            $this->session = $session;
        }

        final public function &getDatabaseConnection(): \App\Core\DatabaseConnection {
            return $this->dbc; // vraca po referenci
        }

        final protected function set(string $key, $value): bool {
            $res = false;
            if (preg_match('/^[a-z][a-z0-9]+(?:[A-Z][a-z0-9]+)*$/', $key)) {
                $this->data[$key] = $value;
                $res = true;
            }
            return $res;
        }

        final public function getData(): array {
            return $this->data;       
        }

        final protected function redirect(string $path, int $code=303){
            ob_clean();
            header('Location:' . $path, true, $code);
            exit;
        }

        protected function sendMessagesToView(){
            $this->set('messages', $this->getSession()->get('messages'));
            $this->getSession()->put('messages', []);
        }

        protected function addMessage($message){
            $messages = $this->getSession()->get('messages');
            $messages[]=$message;

            $this->getSession()->put('messages', $messages);
            $this->getSession()->save();
        }

        protected function sendJSON($fileName, $key=NULL){
            ob_clean();
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $fileName . '.json'); 

            header('Content-type: text/json; charset=UTF-8');
            if($key == NULL){
                $json = json_encode($this->data);
            } else {
                $json = json_encode($this->data[$key]);
            }
            
            echo $json;
            exit;
        }
        
    }
