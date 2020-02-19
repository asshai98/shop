<?php
    namespace App\Models;
    
    use App\Core\Model;
    use App\Core\Field;

    class UserModel extends Model {
        public function getByUsername(string $username) {
            return $this->getByFieldName('username', $username);
        }

        protected function getFields(): array {
            return [
                'user_id' => Field::readonlyInteger(10),

                'forename' => Field::editableString(64),
                'surname' => Field::editableString(128),
				'username' => Field::editableString(64),
                'email' => Field::editableString(128),
                'password_hash' => Field::editableString(128),
                'created_at' => Field::editableString(255),
				'roles' => Field::editableString(64 * 1024),
                'is_visible' => Field::editableBit()
            ];
        }
    }
    
?>