<?php
namespace App\Auth;

use App\DB\DB;

class Auth {

	public static function login($data){ 
        
        if(!isset( $data['email']) || !isset($data['senha']) ){
            $return = array('mensagem'=>'email e senha são obrigatórios!','erro'=>true );
        }else{
        
    		$email = $data['email'];
    		$senha = $data['senha'];
    		
    
    		// Verificar se o e-mail já está em uso
    		$login = DB::prepare("SELECT * FROM tddusu WHERE email = '$email' AND senha = '$senha'");
    		$login->execute();
    
    		if ($login->rowCount() > 0) {
    			$usr = $login->fetchAll()[0];
    			if($usr->ativo == 1){
    			    
    			    // Verificar se o e-mail já está em uso
            		$roles = DB::prepare("SELECT * FROM tddper WHERE usuario_id = '$usr->id'");
            		$roles->execute();
            		
            		    $per = array();
            		
            			if ($roles->rowCount() > 0) {
    			            $per = $roles->fetchAll()[0];
            			}
    		
    				$return = array( 'token'=>'kJbHYGooiBGGhHUIOioHI444NBB4BD5nuw23209N60HY6',
    								 'user'=> $usr,
    								 'roles'=> $per );
    			}else{
    				$return = array('mensagem'=>'Usuario Bloqueado.','erro'=>true );
    			}
    
    		}else{
    			$return = array('mensagem'=>'Usuario ou senha invalido!','erro'=>true );
    		}
        
        }
		return json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

	}


	public static function validarLogin($token){

		if(isset($token[0])){
			if($token[0] == 'kJbHYGooiBGGhHUIOioHI444NBB4BD5nuw23209N60HY6'){
				return true;
			}
		}
		
		return false;
		
	}

}