<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
    'register' => ['RegisterController', 'register',],
    'login' => ['LoginController', 'login',],
    'logout' => ['LoginController', 'logout',],
    'profile' => ['UserController', 'index',],
    'contact/add' => ['ContactController', 'add',],
    'profile/update' => ['UserController', 'update', ['id']],
    'product' => ['ProductController', 'index',],
    'product/show' => ['ProductController', 'show', ['id']],
    'product/search' => ['ProductController', 'searchProduct',],
    'category' => ['CategoryController', 'index', ['id']],
    'cart' => ['CartController', 'index',],
    'cart/add' => ['CartController', 'add', ['id']],
    'cart/delete' => ['CartController', 'decrement', ['id']],
    'cart/unset-one' => ['CartController', 'deleteOneProductInCart', ['id']],
    'cart/remove' => ['CartController', 'cartRemove'],
    'address' => ['AddressController', 'index',],
    'address/add' => ['AddressController', 'add',],
    'address/select' => ['AddressController', 'selectAddress', ['id']],
    'order' => ['OrderController', 'index',],
    'order/show' => ['OrderController', 'show', ['id']],
    'order/add' => ['OrderController', 'orderAdd',],
    'comment/add' => ['CommentController', 'add'],
    'admin' => ['Admin\\AdminController', 'index'],
    'admin/category' => ['Admin\\CategoryAdminController', 'index',],
    'admin/category/create' => ['Admin\\CategoryAdminController', 'create',],
    'admin/category/delete' => ['Admin\\CategoryAdminController', 'delete', ['id']],
    'admin/category/update' => ['Admin\\CategoryAdminController', 'update', ['id']],
    'admin/category/show' => ['Admin\\CategoryAdminController', 'show', ['id']],
    'admin/category/search' => ['Admin\\CategoryAdminController', 'searchCategory',],
    'admin/product' => ['Admin\\AdminProductController', 'index',],
    'admin/product/create' => ['Admin\\AdminProductController', 'create',],
    'admin/product/delete' => ['Admin\\AdminProductController', 'delete', ['id']],
    'admin/product/update' => ['Admin\\AdminProductController', 'update', ['id']],
    'admin/product/show' => ['Admin\\AdminProductController', 'show', ['id']],
    'admin/product/search' => ['Admin\\AdminProductController', 'searchProduct',],
    'admin/user' => ['Admin\\AdminUserController', 'index',],
    'admin/user/update' => ['Admin\\AdminUserController', 'update', ['id']],
];
