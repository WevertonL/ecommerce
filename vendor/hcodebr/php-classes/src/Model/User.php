<?php 
namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{
	const SESSION = "User";

	public static function Login($login, $password)
	{
		$sql = new Sql;

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :Login", [
			":Login" => $login
		]);

		if(count($results) === 0)
		{
			throw new \Exception("Usuário ou senha inválidos", 1);
			
		} else
		{
			$data = $results [0];
			if(!password_verify($password, $data["despassword"]))
			{
				throw new \Exception("Usuário ou senha inválidos", 1);
				
			}else
			{
				$user = new User;

				$user->setData($data);

				$_SESSION[User::SESSION] = $user->getValues();

			}
		}
	}

	public function verifyLogin($inadmin = true)
	{
		if
		(
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		)
		{
			header("Location: /admin/login");
			exit;
		}
	}

	public function logout(){
		unset($_SESSION[User::SESSION]);
	}
}

 ?>