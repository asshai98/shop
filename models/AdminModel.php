<?php
    namespace App\Models;
    
    use App\Core\Model;
    use App\Core\Field;

    class AdminModel extends Model {
        public function getByUsername(string $username) {
            return $this->getByFieldName('username', $username);
        }

        protected function getFields(): array {
            return [
                'admin_id' => Field::readonlyInteger(10),
                'username' => Field::editableString(64),
                'password_hash' => Field::editableString(255),
                'roles' => Field::editableString(64 * 1024),
                'is_visible' => Field::editableBit()
            ];
        }
    }
    
?>