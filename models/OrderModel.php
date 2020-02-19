<?php
    namespace App\Models;

    use App\Core\Model;
    use App\Core\Field;

    class OrderModel extends Model {
        protected function getFields(): array {
            return [
                'order_id' => Field::readonlyInteger(10),

                'created_at' => Field::editableString(64*1024),
                'is_visible' => Field::editableBit(),
                'cart_id' => Field::editableInteger(10),
                'name' => Field::editableString(128),
                'address' => Field::editableString(128)
                
            ];
        }
    }