<?php
require 'bd/bd.php';

if ( isset($_POST['action'])){
	switch ( $_POST['action'] )
	{
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
		//echo "Введеный пароль ". $_POST['params']['passwd'] . "Пароль юзера". $user->password. "логин" .$user->email  ;
 		
		if($user)
		{
			if($_POST['params']['passwd']==$user->password)
			{
				$_SESSION['logged_user']=$user;
				echo $_SESSION['logged_user']->email;
			}
			else{
				$errors[]='неверный пароль';
			}
		}
		else
		{
			$errors[]='Пользователь не найден';
		}


	if(!empty($errors)){
			echo '<div style="color : red;">'.array_shift($errors).'</div><hr>';
			return false;
			 
		}

}


function action_index(){
	
	$comments= R::getAll('SELECT * FROM `genres` ');
	echo "<div id='comment'> <select id='sel'> 
	 <option disabled>Выберите жанр</option>";
	foreach ($comments as $comment) {
		echo  "<option value=".$comment['id'].">".$comment['Name']."</option>";
	}
	echo "</select> </div> ";
}

function findAuthor ($id, $count){
	$author = R::load('authors', $id);

	 echo "Автор : ".$author['name']." Написал : ".$count." книги";
}


function findBook ($id){

	 $book = R::load( 'books', $id );
	 echo $book['name']."<br>";
}




?>
