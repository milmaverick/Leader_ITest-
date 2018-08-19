<?php
require 'bd/bd.php';
define("limit", 5);
function getPage (){
	if(!isset($_POST['page']))
	{
		$page=1;
	}
	else
	{
		$page= $_POST['page'];
	}
	return $page;
}

if ( isset($_POST['action'])){
	switch ( $_POST['action'] )
	{
		case 'isLogged':
	        action_isLogged();
	        break;

	    case 'logOut':
	        action_logOut();
	        break;

	    case 'signin':
	        action_signin();
	        break;
	
	    case 'signup':
	        action_signup();
	        break;

	    case 'author':
	        action_author();
	        break;

	    case 'index':
	        action_index();
	        break;

	    case 'sendMes':
	        action_sendMes();
	        break;
	       
	}
}

function action_index(){
	$page = getPage();
	
	$tpg=($page-1)* limit ;
	$comments= R::getAll('SELECT * FROM `comments` ORDER BY date DESC LIMIT '.$tpg.','.limit.' ');

	foreach ($comments as $comment) {

		if($_SESSION['logged_user']->id==$comment["user_id"])
		{
			echo "<div class='comment my'> ИМЯ: ". $_SESSION['logged_user']->name." |  email:" . $_SESSION['logged_user']->email . 
			"<br> TEXT:".$comment['text']."</div>";
		}
		else{
			$user=findUser($comment["user_id"]);
			echo "<div class='comment another'> ИМЯ: ". $user->name." |  email:" . $user->email . 
			"<br> TEXT:".$comment['text']."</div>";
		}
	}

}


function action_signup(){
	
$errors=array();
	if(trim($_POST['params']['number'])==''){
			{
				$errors[]='Введите номер';
			}
		}
		if(trim($_POST['params']['name'])==''){
			{
				$errors[]='Введите имя';
			}
		}
		if(trim($_POST['params']['email'])==''){
			{
				$errors[]='Введите email';
			}
		}
		if($_POST['params']['passwd']==''){
			{
				$errors[]='Введите пароль';
			}
		}

		if(!preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}
			[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', trim($_POST['params']['email'])))
		{
			$errors[]='Некорректный email';
		}
		if(R::count('test','email=? ',array($_POST['params']['email']) )>0)
			{
				$errors[]='Пользователь с таким email уже существует';
				
			}
		if(!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i', trim($_POST['params']['number'])))
		{
			$errors[]='Некорректный телефон';
		}

		if(empty($errors))
		{
			$comment= R::dispense('test');
			$comment->name = $_POST['params']['name'];
			$comment->email = $_POST['params']['email'];
			$comment->password  = $_POST['params']['passwd'];
			$comment->number = $_POST['params']['number'];

			R::store($comment);
			echo "right";
		}
		else{
			echo array_shift($errors);
		}
	
	
}

function action_signin(){

		$errors= array();
		$user= R::findOne('test','email= ?',array($_POST['params']['email']));
		
		if($user)
		{
			if($_POST['params']['passwd']==$user->password)
			{
				$_SESSION['logged_user']=$user;
				echo "right";
			}
			else{
				$errors[]='неверный пароль';
			}
		}
		else
		{
			$errors[]='Пользователь не найден';
		}


	if(empty($errors)){
			echo array_shift($errors);		 
		}

}


function action_sendMes(){
	$errors=array();
	if(trim($_POST['textMes'])==''){
			{
				$errors[]='Введите текст';
			}
		}
	if(empty($errors))
		{
			$comment= R::dispense('comments');
			$comment->user_id = $_SESSION['logged_user']->id;
			$comment->text = $_POST['textMes'];
			

			R::store($comment);
			echo "right";
		}
		else{
			echo array_shift($errors);
		}

}




function findUser ($id){
	$user= R::findOne('test','id= ?',array($id));

	return $user;
	 
}

function action_isLogged(){
	if(isset($_SESSION['logged_user']))
	{
		echo "right";
	}
	else {
		echo "none";
	}

}

function action_logOut(){

	unset($_SESSION['logged_user']);
	echo "right";
}




?>
