<?php

$config['_default'] = "page/home";
$config['about'] = "page/about";
$config['home'] = "page/home";

$config['users/login'] = "user/login";
$config['users/new'] = "user/add";
$config['users/logout'] = "user/logout";

$config['users/([0-9]+)'] = "user/view/$1";
$config['users/([0-9]+)/([0-9a-zA-Z-_]+)'] = "user/view/$1";
$config['users/edit'] = "user/edit";
$config['users/delete'] = "user/delete";

$config['chocobos/([0-9]+)'] = "chocobo/view/$1";
$config['chocobos/([0-9]+)/([0-9a-zA-Z-_]+)'] = "chocobo/view/$1";

$config['races'] = "race/index";
$config['races/([0-9]+)'] = "race/view/$1";
$config['races/([0-9]+)/register'] = "race/register/$1";
$config['races/([0-9]+)/unregister'] = "race/unregister/$1";

// ADMIN
$config['admin/users'] = "admin/user";
$config['admin/pnjs'] = "admin/user/pnjs";
$config['admin/pnjs/new'] = "admin/user/add";
$config['admin/locations'] = "admin/location";

?>