<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type:application/json;charset=ut8");
require_once  'config/database.php';
require_once  'System/Controller.php';
require_once  'Route/Router.php';
require_once  'Helper/mHelper.php';


Router::start('/user/create','userController@store');
Router::start('/user/info/{id}','userController@info');
Router::start('/user/login','userController@login');
Router::start('/user/update','userController@update');


Router::start('/category/list','categoryController@list');
Router::start('/category/get/{id}','categoryController@get');


Router::start('/posts/list/{id}','postsController@list');
Router::start('/posts/detail/{id}','postsController@detail');


Router::start('/comment/store','commentControllerController@store');
Router::start('/comment/get/{id}','commentController@get');


/*
$token = "123";







$returnArray = [];
$returnArray['status'] = false;

if(isset($_GET['token'])) {

    if($_GET['token'] == $token) {
        $mode = $_GET['mode'];
        $process = $_GET['process'];

        $path = 'Api/' . $mode . '/' . $process . '.php';
        if (file_exists($path)) {
            require_once 'Api/' . $mode . '/' . $process . '.php';
            echo json_encode($returnArray);
        } else {
            die("Page is Not Found");
        }
    }
    else
    {
        die("Token Hatalı");
    }

}
else
{
    die("Token Hatalı");
}
*/
