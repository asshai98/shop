<?php

    return [
        App\Core\Route::get('|^user/register/?$|', 'Main' , 'getRegister'),
        App\Core\Route::post('|^user/register/?$|', 'Main' , 'postRegister'),

        App\Core\Route::get('|^user/login/?$|', 'Main' , 'getLogin'),
        App\Core\Route::post('|^user/login/?$|', 'Main' , 'postLogin'),

        App\Core\Route::get('|^admin/login/?$|', 'Main' , 'getAdminLogin'),
        App\Core\Route::post('|^admin/login/?$|', 'Main' , 'postAdminLogin'),


        #User role rute
        App\Core\Route::get('|^user/profile/?$|', 'UserDashboard' , 'index'),
        App\Core\Route::get('|^admin/profile/?$|', 'AdminDashboard' , 'index'),

		#Admin managment rute - kategorije
        App\Core\Route::get('|^admin/categories/?$|', 'AdminCategoryManagment' , 'categories'),
        App\Core\Route::get('|^admin/reports/?$|', 'Report' , 'getReports'),
        

        App\Core\Route::get('|^admin/categories/edit/([0-9]+)?$|', 'AdminCategoryManagment' , 'getEdit'),
        App\Core\Route::post('|^admin/categories/edit/([0-9]+)?$|', 'AdminCategoryManagment' , 'postEdit'),

        App\Core\Route::get('|^admin/categories/add/?$|', 'AdminCategoryManagment' , 'getAdd'),
        App\Core\Route::post('|^admin/categories/add/?$|', 'AdminCategoryManagment' , 'postAdd'),
        App\Core\Route::get('|^admin/categories/delete/([0-9]+)?$|', 'AdminCategoryManagment' , 'getDelete'),


        #Admin managment rute - itemi

        App\Core\Route::get('|^admin/items/?$|', 'AdminItemManagment' , 'items'),

        App\Core\Route::get('|^admin/items/edit/([0-9]+)?$|', 'AdminItemManagment' , 'getEdit'),
        App\Core\Route::post('|^admin/items/edit/([0-9]+)?$|', 'AdminItemManagment' , 'postEdit'),

        App\Core\Route::get('|^admin/items/add/?$|', 'AdminItemManagment' , 'getAdd'),
        App\Core\Route::post('|^admin/items/add/?$|', 'AdminItemManagment' , 'postAdd'),

        App\Core\Route::get('|^admin/profile/viewYearDetails/([0-9]+)/?$|', 'Report' , 'getDetailsByYear'),
        App\Core\Route::get('|^admin/profile/viewItemDetails/([0-9]{4}-[0-9]{2}-[0-9]{2})/([0-9]{4}-[0-9]{2}-[0-9]{2})/?$|', 'Report' , 'getItemDetails'),
        App\Core\Route::get('|^admin/profile/viewBuyerDetails/([0-9]+)/?$|', 'Report' , 'getBuyerDetails'),

        App\Core\Route::get('|^admin/profile/viewBuyerDetails/([0-9]+)/json/?$|', 'Report' , 'exportBuyerDetails'),
        App\Core\Route::get('|^admin/profile/viewItemDetails/([0-9]{4}-[0-9]{2}-[0-9]{2})/([0-9]{4}-[0-9]{2}-[0-9]{2})/json/?$|', 'Report' , 'exportByDateDetails'),
        App\Core\Route::get('|^admin/profile/viewYearDetails/([0-9]+)/json/?$|', 'Report' , 'exportByYearDetails'),

        App\Core\Route::get('|^admin/items/delete/([0-9]+)?$|', 'AdminItemManagment' , 'getDelete'),

        App\Core\Route::get('|^user/logout/?$|', 'Main' , 'getLogout'),
        App\Core\Route::get('|^admin/logout/?$|', 'Main' , 'getAdminLogout'),

        App\Core\Route::get('|^item/buy/([0-9]+)?$|', 'Cart' , 'getAdd'),
        App\Core\Route::get('|^user/cart/?$|', 'Cart' , 'viewCart'),
        App\Core\Route::get('|^user/cart/delete/([0-9]+)/?$|', 'Cart' , 'deleteItem'),
        App\Core\Route::post('|^user/cart/order/?$|', 'Cart' , 'postOrder'),
        
		
        App\Core\Route::get('|^category/([0-9]+)(?:/([0-9]+))?/?$|','Category','show'),
        App\Core\Route::get('|^item/([0-9]+)/?$|','Item','show'),
        App\Core\Route::any('|^.*$|', 'Main', 'home') //fallback ruta
        
    ];