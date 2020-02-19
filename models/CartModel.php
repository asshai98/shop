<?php
    namespace App\Models;

    use App\Core\Model;
    use App\Core\Field;

    class CartModel extends Model {
        protected function getFields(): array {
            return [
                'cart_id' => Field::readonlyInteger(10),

                'user_id' => Field::editableInteger(10)
            ];
        }

        public function getLatestUnusedCart($userId){
            $sql = "SELECT * FROM `cart` WHERE user_id=? AND cart_id NOT IN (SELECT cart_id FROM `order`) 
                    ORDER BY cart_id DESC ";

            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$userId]);
            $item = NULL;
            if($res) {
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }

            return $item;
        }

        public function addItem($cartId, $itemId) {
            $sql = "INSERT INTO cart_item SET cart_id = ?, item_id = ?";

            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$cartId, $itemId]);

            return $this->getConnection()->lastInsertId();

        }

        public function getCartContent($cartId) {
            $sql = "SELECT item.item_id,item.`name`, COUNT(cart_item.item_id) AS number_of_items, item.price, COUNT(cart_item.item_id)*item.price AS total FROM cart_item INNER JOIN item ON cart_item.item_id = item.item_id 
                    WHERE cart_item.cart_id = ? AND cart_item.is_visible=1 GROUP BY cart_item.item_id";

            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$cartId]);

            return $prep->fetchAll(\PDO::FETCH_OBJ);

        }

        public function deleteOneItem($itemId, $cartId){
            $sql = "UPDATE cart_item SET cart_item.is_visible = 0 WHERE cart_item.item_id = ? AND cart_item.cart_id=? 
                    AND cart_item.is_visible=1 LIMIT 1;";
            $prep = $this->getConnection()->prepare($sql);
            return $prep->execute([$itemId, $cartId]);
        }

        public function itemCounter($cartId) {
            $sql = "SELECT COUNT(cart_item.item_id) AS total_item FROM cart_item  WHERE cart_item.cart_id = ? 
                    AND cart_item.is_visible = 1";

            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$cartId]);


            return intVal($prep->fetch(\PDO::FETCH_OBJ) -> total_item);
        }

        public function createOrder($cartId,$name,$address){
            $sql = "INSERT INTO `order` (cart_id, name, address) VALUES (?,?,?)";

            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$cartId,$name, $address]);

            


            return $this->getConnection()->lastInsertId();
        }
        
    }
    
?>