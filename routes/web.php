<?php
use \Engine\Routing\Routing;

/*
 * Auth routes
 *
 * */
Routing::get('/login','AuthController@login');
Routing::post('/login','AuthController@login');
Routing::get('/register','AuthController@register');
Routing::post('/register','AuthController@register');


/*
 * Main pages routes
 *
 * */
Routing::get('/','HomeController@index');
Routing::get('/manage','TaskController@manage');
Routing::get('/tasks','TaskController@index');
Routing::get('/create','TaskController@create');
Routing::get('/projects','ProjectsController@index');
Routing::post('/projects/create','ProjectsController@create');
Routing::get('/project/{id}','ProjectsController@single');
/*
 * Task routes & API
 *
 * */
Routing::get('/task/{id}','TaskController@single');
Routing::post('/getcsvdata','HomeController@getCsvData');
Routing::post('/chartdata','HomeController@chartdata');
Routing::post('/create','TaskController@create');
Routing::post('/createmanage','TaskController@createManage');
Routing::post('/task/delete','TaskController@delete');
Routing::post('/deletemanage','TaskController@deleteManage');
Routing::post('/updatemanage','TaskController@updateManage');
Routing::post('/updatetask','TaskController@update');