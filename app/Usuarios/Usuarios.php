<?php 
namespace App\Usuarios;

use App\DB\DB;

class Usuarios {

	public static function getAllUsuauios($id_admin){ 
		
		$sql = "SELECT * FROM tddusu";

		if(isset($id_admin[0])){ 
			$sql .= " WHERE id_admin = '$id_admin[0]' ";
		}

		$stmt = DB::prepare($sql);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}

	public static function getIdUsuauios($id){ 
		
		$stmt = DB::prepare("SELECT * FROM tddusu WHERE id = :id");
		$stmt->bindParam(':id', $id);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll();  
		if($resultados[0]){
			$rest = json_encode(  $resultados[0] , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 
		}

		return $rest;
	}

	public static function addUsuauios($data){ 
		//valida nome
		if (isset($data['nome'])) {
			if(empty($data['nome'])){
				$return = array('mensage'=>'propriedade nome vazio!','erro'=>true );
			}
		} else {
			$return = array('mensage'=>'propriedade nome não informado!','erro'=>true );
		}
		//valida email
		if (isset($data['email'])) {
			if(empty($data['email'])){
				$return = array('mensagem'=>'propriedade email vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade email não informado!','erro'=>true );
		}
		//valida senha
		if (isset($data['senha'])) {
			if(empty($data['senha'])){
				$return = array('mensagem'=>'propriedade senha vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade senha não informado!','erro'=>true );
		}
		
		if(!isset($return)){

			$nome  = $data['nome'];
			$email = $data['email'];
			$senha = $data['senha'];
		
			// Verificar se o e-mail já está em uso
			$verificarEmail = DB::prepare("SELECT * FROM tddusu WHERE email = '$email'");
			$verificarEmail->execute();
		
			if ($verificarEmail->rowCount() > 0) {
				$return = array('mensagem'=>'E-mail já em uso. Por favor, escolha outro.','erro'=>true );
			}else{

				// Inserir usuário no banco de dados
				$inserirUsuario = DB::prepare("INSERT INTO tddusu (nome, email, senha, id_admin) VALUES (:nome,:email,:senha,:id_admin)");
				$var = 0;
				$inserirUsuario->bindParam(':nome' 	  , $nome);
				$inserirUsuario->bindParam(':email'	  , $email);
				$inserirUsuario->bindParam(':senha'	  , $senha);
				$inserirUsuario->bindParam(':id_admin', $var);
				
				if($inserirUsuario->execute()){
					$verificarEmail = DB::prepare("SELECT * FROM tddusu WHERE email = '$email'");
					$verificarEmail->execute();
					$return = $verificarEmail->fetchAll(); 
				}else{
					$return = array('mensagem'=>'Ocorreu um erro verifique com o suporte!','erro'=>true );
				}

			}
		
		}
        
		return json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

	}

	public static function addUsuauiosFilhos($data,$id_admin){ 

		//valida nome
		if (isset($data['nome'])) {
			if(empty($data['nome'])){
				$return = array('mensage'=>'propriedade nome vazio!','erro'=>true );
			}
		} else {
			$return = array('mensage'=>'propriedade nome não informado!','erro'=>true );
		}
		//valida email
		if (isset($data['email'])) {
			if(empty($data['email'])){
				$return = array('mensagem'=>'propriedade email vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade email não informado!','erro'=>true );
		}
		//valida senha
		if (isset($data['senha'])) {
			if(empty($data['senha'])){
				$return = array('mensagem'=>'propriedade senha vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade senha não informado!','erro'=>true );
		}

		if(!isset($id_admin[0])){
			$return = array('mensagem'=>'id Admin não informado!','erro'=>true ); 
		}
		
		if(!isset($return)){

			$nome  = $data['nome'];
			$email = $data['email'];
			$senha = $data['senha'];

			$idadmin = $id_admin[0];

			// Verificar se o e-mail já está em uso
			$admin = DB::prepare("SELECT * FROM tddusu WHERE id = '$idadmin'");
			$admin->execute();
			$usrAdmin = $admin->fetchAll();
		
			if ($admin->rowCount() > 0) {
				
				if( $usrAdmin[0]->ativo == 2){
					$return = array('mensagem'=>'Usuario superior não pode cadastrar.','erro'=> true );
				}
				
				if( $usrAdmin[0]->id_admin <> 0 ){
					$return = array('mensagem'=>'Usuario não é superior não pode cadastrar.','erro'=>true );
				}

			}else{
				$return = array('mensagem'=>'Usuario superior não existe','erro'=>true );
			}
		
			// Verificar se o e-mail já está em uso
			$verificarEmail = DB::prepare("SELECT * FROM tddusu WHERE email = '$email'");
			$verificarEmail->execute();
		
			if ($verificarEmail->rowCount() > 0) {
				$return = array('mensagem'=>'E-mail já em uso. Por favor, escolha outro.','erro'=>true );
			}
			
			if(!isset($return)){

				$inserirUsuario = DB::prepare("INSERT INTO tddusu (nome, email, senha, id_admin ) VALUES (:nome ,:email ,:senha ,:idadmin )");

				$inserirUsuario->bindParam(':nome' 	  , $nome);
				$inserirUsuario->bindParam(':email'	  , $email);
				$inserirUsuario->bindParam(':senha'	  , $senha);
				$inserirUsuario->bindParam(':idadmin' , $idadmin);
				
				// if($inserirUsuario->execute()){
				// 	$verificarEmail = DB::prepare("SELECT * FROM tddusu WHERE email = '$email'");
				// 	$verificarEmail->execute();
				// 	$return = $verificarEmail->fetchAll(); 
				// }else{
				// 	$return = array('mensagem'=>'Ocorreu um erro verifique com o suporte!','erro'=>true );
				// }
						// Executa a consulta
				if ($inserirUsuario->execute()) {
					return json_encode(array('mensagem'=>"Usuario INCLUIDO com sucesso!" ));
				} else {
					return json_encode(array('mensagem'=>"Falha ao incluir Usuario",'erro'=>true ));
				}

			}
		
		}
        
		return json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

	}


	public static function editarUsuauios($data,$id_admin,$id){ 

		if(!isset($id_admin[0])){ 
			$return = json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		//valida nome
		if (isset($data['nome'])) {
			if(empty($data['nome'])){
				$return = array('mensage'=>'propriedade nome vazio!','erro'=>true );
			}
		} else {
			$return = array('mensage'=>'propriedade nome não informado!','erro'=>true );
		}
		//valida email
		if (isset($data['email'])) {
			if(empty($data['email'])){
				$return = array('mensagem'=>'propriedade email vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade email não informado!','erro'=>true );
		}
		//valida senha
		if (isset($data['senha'])) {
			if(empty($data['senha'])){
				$return = array('mensagem'=>'propriedade senha vazio!','erro'=>true );
			}
		} else {
			$return = array('mensagem'=>'propriedade senha não informado!','erro'=>true );
		}
		
		if(!isset($return)){

			$nome  = $data['nome'];
			$email = $data['email'];
			$senha = $data['senha'];
		
			// Verificar se o e-mail já está em uso
			$verificarEmail = DB::prepare("SELECT * FROM tddusu WHERE email = '$email'");
			$verificarEmail->execute();
		
			if (!$verificarEmail->rowCount() > 0) {
				$return = array('mensagem'=>'E-mail não em uso. Por favor, escolha outro.','erro'=>true );
			}else{

				$atualizarUsuario = DB::prepare("UPDATE tddusu SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
				$atualizarUsuario->bindParam(':id', $id);
				$atualizarUsuario->bindParam(':nome', $nome);
				$atualizarUsuario->bindParam(':email', $email);
				$atualizarUsuario->bindParam(':senha', $senha);

				// var_dump($atualizarUsuario->execute());
				
				if ($atualizarUsuario->execute()) {
					return json_encode(array('mensagem'=>"Usuario ALTERADO com sucesso!" ));
				} else {
					return json_encode(array('mensagem'=>"Falha ao incluir Usuario",'erro'=>true ));
				}

			}
		
		}
        
		return json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

	}

}