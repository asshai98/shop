<?php
    namespace App\Models;

    use App\Core\Model;
    use App\Core\Field;

    class CategoryModel extends Model {
        protected function getFields(): array {
            return [
                'category_id' => Field::readonlyInteger(10),

                'name' => Field::editableString(64),
                'cat_desc' => Field::editableString(64 * 1024),
                'is_visible' => Field::editableBit(),
                'admin_id' => Field::editableInteger(10)
            ];
        }

        
        
    }
    
?>