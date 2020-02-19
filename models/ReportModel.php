<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Field;

class ReportModel extends Model {


    public function viewOrderDetailsByYear($year){
        $sql = 'SELECT 
                MONTH(`date`) AS `month`, 
                SUM(total) AS total
                FROM view__order_details 
                WHERE YEAR(`date`) = ?
                GROUP BY MONTH(`date`) 
                ORDER BY MONTH(`date`) ';
        $prep = $this->getConnection()->prepare($sql);
        $prep->execute([$year]);
        return $prep->fetchAll(\PDO::FETCH_OBJ);

    }

    public function viewItemDetails($dateFrom, $dateTo){
        $sql = 'SELECT 
                `item`.`name`,
                SUM(`item`.price) AS total,
                COUNT(cart_item.cart_item_id) AS `count`
                FROM `item`
                INNER JOIN cart_item ON item.item_id = cart_item.item_id
                INNER JOIN `cart` ON `cart_item`.cart_id = `cart`.cart_id 
                INNER JOIN `order` ON `order`.cart_id = `cart`.cart_id 
                WHERE `order`.is_visible = 1 AND DATE(`order`.created_at) BETWEEN ? AND ?
                GROUP BY `item`.item_id
                ORDER BY SUM(item.price) DESC
                LIMIT 10';
        $prep = $this->getConnection()->prepare($sql);
        $prep->execute([$dateFrom, $dateTo]);
        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }

    public function viewBuyerDetails($year){
        $sql = 'SELECT 
                `user`.`username`,
                SUM(`item`.price) AS total,
                COUNT(cart_item.cart_item_id) AS amount,
                `user`.`email`
                FROM `item`
                INNER JOIN cart_item ON item.item_id = cart_item.item_id
                INNER JOIN `cart` ON `cart_item`.cart_id = `cart`.cart_id 
                INNER JOIN `user` ON `cart`.user_id = `user`.user_id
                INNER JOIN `order` ON `order`.cart_id = `cart`.cart_id 
                WHERE `order`.is_visible = 1 AND YEAR(`order`.`created_at`) = ?
                GROUP BY `user`.user_id
                ORDER BY SUM(item.price) DESC
                LIMIT 10';

        $prep = $this->getConnection()->prepare($sql);
        $prep->execute([$year]);
        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getDistinctYears(){
        $sql = 'SELECT DISTINCT YEAR(`date`) AS `year` FROM view__order_details ORDER BY YEAR(`date`);';

        $prep = $this->getConnection()->prepare($sql);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getDistinctDates(){
        $sql = 'SELECT DISTINCT DATE(`date`) AS `date` FROM view__order_details ORDER BY DATE(`date`);';

        $prep = $this->getConnection()->prepare($sql);
        $prep->execute();
        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }


}