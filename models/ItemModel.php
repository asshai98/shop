<?php
    namespace App\Models;
    
    use \App\Core\Model;
    use \App\Core\Field;
    
    class ItemModel extends Model {
        protected function getFields(): array {
            return [
                'item_id' => Field::readonlyInteger(10),

                'name' => Field::editableString(128),
                'price' => Field::editableMaxDecimal(7, 2),
                'description' => Field::editableString(64 * 1024),
                'is_visible' => Field::editableBit(),
                'category_id' => Field::editableInteger(10),
                'admin_id' => Field::editableInteger(10)
            ];

        }

        public function getAllVisibleByCat(int $catId): array {
            return $this->getAllByFieldName('category_id', $catId);
        }

        public function getAllVisibleByCategoryIdPerPage(int $catId, $page): array {
            $from = max(0, intval($page) - 1) * 4;
            $sql = "SELECT item.*
                    FROM item
                    WHERE item.category_id = ? AND item.is_visible = 1
                    ORDER BY item.`name`
                    LIMIT {$from}, 4";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$catId]);
            $items = [];
            if($res) {
                $items = $prep->fetchAll(\PDO::FETCH_OBJ);
            }
            return $items;
        }
    } 
