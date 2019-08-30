<?php
date_default_timezone_set('America/Sao_Paulo');
class Upload{
	private $db;

	public function __construct(){
		$optionss = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
		$file = file_get_contents('config.json');
		$options = json_decode($file, true);

		$config = array();

		$config['db'] = $options['db'];
		$config['host'] = $options['localhost'];
		$config['user'] = $options['user'];
		$config['pass'] = $options['pass'];

		try {
			$this->db = new PDO("mysql:dbname=".$config['db'].";host=".$config['host']."", "".$config['user']."", "".$config['pass']."", $optionss);
		} catch(PDOException $e) {
			echo "FALHA: ".$e->getMessage();
		}
	}
	
	public function setUpload($musica, $descricao){

		$sql = $this->db->prepare("
			INSERT INTO musicas 
			SET 
			musica = :musica, 
			descricao = :descricao");
		$sql->bindValue(":musica", $musica);
		$sql->bindValue(":descricao", $descricao);
		$sql->execute();

		return true;
	}
	public function getUpload(){
		$sql = $this->db->prepare("SELECT * FROM musicas");
		$sql->execute();

		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getUploadUnique($id = ''){
		if (!empty($id)) {
			$sql = $this->db->prepare("SELECT * FROM musicas WHERE id = :id");
			$sql->bindValue(':id', $id);
			$sql->execute();

			return $sql->fetch(PDO::FETCH_ASSOC);
		} else {
			$sql = $this->db->prepare("SELECT * FROM musicas ORDER BY RAND() LIMIT 1");
			$sql->execute();

			return $sql->fetch(PDO::FETCH_ASSOC);
		}
	}
	public function delUpload($id){
		$sql = $this->db->prepare("DELETE FROM musicas WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();

		return true;
	}
	public function countMusicas(){
		$sql = $this->db->prepare("SELECT COUNT(*) as c FROM musicas");
		$sql->execute();

		return $sql->fetch(PDO::FETCH_ASSOC);
	}

}