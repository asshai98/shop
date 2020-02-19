<?php
    namespace App\Core\Role;

    class UserRoleController extends \App\Core\Controller {
        public function __pre() {
            $userId = $this->getSession()->get('user_id', null);

            if ($userId === null){
                $this->redirect('/user/login');
            }
        }
    }