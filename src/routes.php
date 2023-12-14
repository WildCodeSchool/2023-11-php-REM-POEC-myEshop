<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'admin' => ['AdminController', 'index'],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
     'register' => ['RegisterController', 'register',],
    'login' => ['LoginController', 'login',],
    'logout' => ['LoginController', 'logout',],
    'admin/category' => ['Admin\\CategoryAdminController', 'index',],
    'admin/category/create' => ['Admin\\CategoryAdminController', 'create',],
    'admin/category/delete' => ['Admin\\CategoryAdminController', 'delete', ['id']],
    'admin/category/update' => ['Admin\\CategoryAdminController', 'update', ['id']],
    'admin/category/show' => ['Admin\\CategoryAdminController', 'show', ['id']],
];
