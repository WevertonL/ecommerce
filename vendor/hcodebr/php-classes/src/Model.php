<?php 
namespace Hcode;

class Model
{
	private $values = [];


	//Cria os métodos Get e Set dinamicamente quando usado o método setData
	public function __call($name, $values)
	{
		$method = substr($name, 0,3);
		//Armazena o nome do campo no banco de dados.
		$fieldName = substr($name, 3,strlen($name));

		switch ($method) 
		{
			case 'get':
					// Retorna os valores armazenados pelo método setData
					return $this->values[$fieldName];
				break;
			
			case 'set':
					//Armazena os valores atribuidos pelo método @setData no atributo values criando uma chave com o nome do campo usando a variável fieldName.
					$this->values[$fieldName] = $values[0];			
				break;
		}
	}


	// Cria dinamicamente os métodos Set do array informado na declaração do método.
	public function setData($data)
	{
		foreach ($data as $key => $value) {
			$this->{"set".$key}($value);
		}
	}

	public function getValues()
	{
		return $this->values;
	}
}

 ?>