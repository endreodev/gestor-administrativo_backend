<?php 
namespace App\Empresas;

use App\DB\DB;

class Empresas {

    //BUSCA TODAS EMPRESAS DO USUARIO 
	public static function getAllEmpresas($id_admin){ 

		$sql = "SELECT * FROM tddemp";

		if(isset($id_admin[0])){ 
			$sql .= " WHERE id_admin = '$id_admin[0]' ";
		}else{
		    return json_encode(array('mensagem'=>'Usuario superior não pode cadastrar.','erro'=> true )); 
		}

		$stmt = DB::prepare($sql);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}

	public static function getIdEmpresas($id){ 
		
		$rest = json_encode(array());
		$stmt = DB::prepare("SELECT * FROM tddemp WHERE id = :id");
		$stmt->bindParam(':id', $id);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
		if($resultados[0]){
			$rest = json_encode(  $resultados[0] , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 
		}
        // $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}

	public static function addEmpresas($data,$id_admin){ 

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}else{
			// Verificar se o e-mail já está em uso
			$admin = DB::prepare("SELECT * FROM tddusu WHERE id = '$id_admin[0]'");
			$admin->execute();
			$usrAdmin = $admin->fetchAll();
		
			if ($admin->rowCount() > 0) {
				
				if( $usrAdmin[0]->ativo == 2){
					return json_encode(array('mensagem'=>'Usuario superior não pode cadastrar.','erro'=> true ));
				}
				
				if( $usrAdmin[0]->id_admin <> 0 ){
					return json_encode(array('mensagem'=>'Usuario não é superior não pode cadastrar.','erro'=>true ));
				}

			}else{
				return json_encode(array('mensagem'=>'Usuario superior não existe','erro'=>true ));
			}
		}

		// Verifica se todos os campos obrigatórios estão presentes
		$camposObrigatorios = ['cgc','nome','cep','endereco','estado','cidade','bairro','numero','telefone','email'];

		foreach ($camposObrigatorios as $campo) {
			if (!isset($data[$campo]) || empty($data[$campo])) {
				return json_encode(array('mensagem'=>"O campo '$campo' é obrigatório.",'erro'=>true ));
			}
		}

		// Verificar se o e-mail já está em uso
		$verificarCgc = DB::prepare("SELECT * FROM tddemp WHERE cgc = '".$data['cgc']."'");
		$verificarCgc->execute();
	
		if ($verificarCgc->rowCount() > 0) {
			return json_encode(array('mensagem'=>"CNPJ ja cadastrado",'erro'=>true ));
		}
	
		// Criação da instância da sua classe DB
		$db = DB::getInstance();
	
		// Insere os dados no banco de dados
		$sql = 'INSERT INTO tddemp (  cgc,  nome,  cep,  endereco,  estado,  cidade, bairro,  numero,  telefone,  email, id_admin)
					        VALUES ( :cgc, :nome, :cep, :endereco, :estado, :cidade, :bairro, :numero, :telefone, :email, :id_admin)';
	
		$stmt = $db->prepare($sql);
	
		foreach ($camposObrigatorios as $campo) {
			$stmt->bindParam(":$campo", $data[$campo]);
		}

		$stmt->bindParam(':id_admin', $id_admin[0] );
	
		// Executa a consulta
		if ($stmt->execute()) {
			return json_encode(array('mensagem'=>"Empresa cadastrada com sucesso!" ));
		} else {
			return json_encode(array('mensagem'=>"Falha ao incluir empresa",'erro'=>true ));
		}


		
	}


	public static function editarEmpresas($data,$id_admin,$id){ 

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}else{
			// Verificar se o e-mail já está em uso
			$admin = DB::prepare("SELECT * FROM tddusu WHERE id = '$id_admin[0]'");
			$admin->execute();
			$usrAdmin = $admin->fetchAll();
		
			if ($admin->rowCount() > 0) {
				
				if( $usrAdmin[0]->ativo == 2){
					return json_encode(array('mensagem'=>'Usuario superior não pode cadastrar.','erro'=> true ));
				}
				
				if( $usrAdmin[0]->id_admin <> 0 ){
					return json_encode(array('mensagem'=>'Usuario não é superior não pode cadastrar.','erro'=>true ));
				}

			}else{
				return json_encode(array('mensagem'=>'Usuario superior não existe','erro'=>true ));
			}
		}

		// Verifica se todos os campos obrigatórios estão presentes
		$camposObrigatorios = ['nome','cep','endereco','estado','cidade','bairro','numero','telefone','email'];

		foreach ($camposObrigatorios as $campo) {
			if (!isset($data[$campo]) || empty($data[$campo])) {
				return json_encode(array('mensagem'=>"O campo '$campo' é obrigatório.",'erro'=>true ));
			}
		}

		// Verificar se o e-mail já está em uso
		$verificarCgc = DB::prepare("SELECT * FROM tddemp WHERE cgc = '".$data['cgc']."'");
		$verificarCgc->execute();
	
		if (!$verificarCgc->rowCount() > 0) {
			return json_encode(array('mensagem'=>"Empresa não encontrada!",'erro'=>true ));
		}
	
		// Criação da instância da sua classe DB
		$db = DB::getInstance();
	
		// UPDATE os dados no banco de dados
		$sql = 'UPDATE tddemp 
				SET nome = :nome, 
					cep = :cep, 
					endereco = :endereco, 
					estado = :estado, 
					cidade = :cidade, 
					bairro = :bairro, 
					numero = :numero, 
					telefone = :telefone, 
					email = :email
				WHERE id = :id';
	
		$stmt = $db->prepare($sql);
	
		foreach ($camposObrigatorios as $campo) {
			$stmt->bindParam(":$campo", $data[$campo]);
		}

		$stmt->bindParam(':id', $id );
	
		// Executa a consulta
		if ($stmt->execute()) {
			return json_encode(array('mensagem'=>"Empresa alterada com sucesso!" ));
		} else {
			return json_encode(array('mensagem'=>"Falha ao incluir empresa",'erro'=>true ));
		}
		
	}



}