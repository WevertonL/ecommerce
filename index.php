<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\pageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

	$page = new Page();

	$page->setTpl("index");
});

$app->get('/admin', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");
});

$app->get('/admin/login', function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
});

$app->post('/admin/login', function() {

	User::Login($_POST["login"],$_POST["password"]);

	header("Location: /admin");
	exit;
});

$app->get('/admin/logout', function() {

	User::logout();

	header("Location: /admin/login");
	exit;
});

// Rota de Lista de usuários
$app->get('/admin/users', function() {

	User::verifyLogin();
	$page = new PageAdmin();

	$users = User::listAll();

	$page->setTpl("users", ["users"=>$users]);
});

// Rota para inserção de usuários, executando apenas o HTML
$app->get('/admin/users/create', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

// Rota para exclusão de usuários
$app->get('/admin/users/:iduser/delete', function($iduser) {

	User::verifyLogin();

	$user = new User;

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

// Rota para realizar update de usuário
$app->get('/admin/users/:idusers', function($iduser) {

	User::verifyLogin();
	$user = new User;
	$page = new PageAdmin();

	$user->get((int)$iduser);

	$page->setTpl("users-update", ["user"=>$user->getValues()]);
});

// Rota que confirma o cadastro de usuário através do envio do formulário
$app->post('/admin/users/create', function() {
	User::verifyLogin();
	$user = new User;

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
});

// Rota que confirma a edição de usuários
$app->post('/admin/users/:idusers', function($iduser) {
	User::verifyLogin();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user = new User;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update($iduser);

	header("Location: /admin/users");
	exit;
});



$app->run();

 ?>