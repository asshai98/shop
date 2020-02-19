<?php

namespace App\Controllers;

class ItemController extends \App\Core\Controller {

    public function show($id) {
        #$this->requires(['user.category.show', 'user.category.list']);
        $itemModel = new \App\Models\ItemModel($this->getDatabaseConnection());
        $item = $itemModel->getById($id);
        if (!$item){
            ob_clean();
            header("Location: " . \Config::BASE_URL);
            exit;
        }
        $this->set('item', $item);
        $this->sendMessagesToView();

        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $category = $categoryModel->getById($item->category_id);
        $this->set('category', $category);

        $this->addBreadcrum("/", "Home");
        $this->addBreadcrum("/category/" . $category->category_id, $category->name);
        $this->addBreadcrum("/item/" . $item->item_id, $item->name);
        
    }
}