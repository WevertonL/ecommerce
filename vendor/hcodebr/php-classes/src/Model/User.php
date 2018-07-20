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

	public static function verifyLogin($inadmin = true)
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

	public static function logout()
	{
		unset($_SESSION[User::SESSION]);
	}

	public static function listAll()
	{
		$sql = new Sql;

	return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY desperson");
	}

	public function save()
	{
		$sql = new Sql;

		$results = $sql->select("CALL sp_users_save(:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)", [
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		]);


		$this->setData($results[0]);
	}

	public function get($iduser)
	{
		$sql = new Sql;

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", [":iduser"=>$iduser]);

		$this->setData($results[0]);
	}

	public function update($iduser)
	{
		$sql = new Sql;

		$results = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)", [
			":iduser"=>$iduser,
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		]);


		$this->setData($results[0]);
	}

	public function delete()
	{
		$sql = new Sql;

		$sql->select("CALL sp_users_delete(:iduser)",[":iduser"=>$this->getiduser()]);
	}
}

 ?>