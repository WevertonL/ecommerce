<?php 
namespace Hcode;

use Rain\Tpl;

class Page{
	//Atributo responsável pela instancia da classe Rain TPL.
	private $tpl;

	//Atributo que recebe a mesclagem do atributo defaults e os opts do __construct.
	private $options;

	//Atributo que recebe as variáveis padrão.
	private $defaults = [
		"data"=>[]
	];

	// Construtor recebe um array com váriaveis, caso não informado ele recebe as variaveis defaults caso informado subistitui a defaults com o comando merge se necessário.
	public function __construct($opts = array())
	{
		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			//Diretório onde será armazenado o código HTML das paginas do projeto.
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
			//Diretório onde será armazenados os caches do projeto
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
	   );

		// Executa a configuação do RainTpl conforme informado no array acima.
		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);

		$this->tpl->draw("header");
	}

	public function setTpl($name, $data = array(), $returnHTML = false)
	{
		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);
	}

	private function setData($data = array())
	{
		foreach ($data as $key => $value) 
		{
			$this->tpl->assign($key,$value);
		}
	}

	public function __destruct(){
		$this->tpl->draw("footer");
	}


}

 ?>