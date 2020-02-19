<?php
    namespace App\Controllers;

class CategoryController extends \App\Core\Controller {
    public function show($id, $page=1){
        #$this->requires(['user.category.show']);

        if ($page == null || $page == '') {
            $page = 1;
        }

        $this->set('page', $page);

        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $category = $categoryModel->getById($id);
        $this->set('category', $category);
        
        $itemModel = new \App\Models\ItemModel($this->getDatabaseConnection());
        $items = $itemModel->getAllVisibleByCategoryIdPerPage($id, $page);
        $this->set('items', $items);

        if ($page > 1 &&  count($items) == 0) {
            $this->redirect('/category/' . $category->category_id . '/' . ($page-1));

        } 

        #$numberOfItems = count($items);
        #$this->set('numberOfItems', $numberOfItems);
        
        $this->addBreadcrum("/", "Home");
        $this->addBreadcrum("/category/" . $category->category_id, $category->name);
    }    
}