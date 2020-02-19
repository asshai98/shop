<?php
    namespace App\Core\Role;

    class AdminRoleController extends \App\Core\Controller{
        public function __pre() {
            $adminId = $this->getSession()->get('admin_id', null);

            if ($adminId === null){
                $this->redirect('/admin/login');
            }
        }
    }