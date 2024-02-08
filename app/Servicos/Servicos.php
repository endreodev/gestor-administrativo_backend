<?php
namespace App\Servicos;

use stdClass;
use App\DB\DB;
use PDOException;

class Servicos {


	public static function getAllServicos($id_admin,$empresa_id=null,$filtro=null){ 

		$addSql = "";
		$addSql1 = "";

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}
        //  
        
		if(isset($empresa_id) && $empresa_id != "0" ){
			$addSql1 = " AND empresa_id = '".$empresa_id."' ";
		}

		if(isset($filtro)){
			if($filtro == "vencido"){
				$addSql = " AND DATE_FORMAT(data_fim,'%Y-%m-%d') < DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') ";
			}else if($filtro == "hoje"){
			    $addSql = " AND DATE_FORMAT(data_fim,'%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') ";
			}
			else{
				$addSql = " AND status = '".$filtro."' ";
			}
		}

		$sql = "
		    SELECT ser.* , emp.nome as empresa_nome , tip.descricao as tipo_descricao
		    FROM tddser ser 
		    inner join tddemp emp on emp.id = ser.empresa_id and emp.id_admin = ser.id_admin 
		    inner join fomulario_tipo tip on tip.id = ser.tipo_id and tip.id_admin = ser.id_admin 
		    WHERE ser.id_admin = :id_admin ".$addSql." ".$addSql1." ";

// 		var_dump($sql);

		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id_admin', $id_admin[0]);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	} 
	
	
	
	public static function getStatusServicos($id_admin){ 

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true )); 
		}

		return json_encode(array('status'=>array('','aberto','andamento','finalizado','hoje','vencido') ));
	} 


	public static function getResumoServicos($id_admin){ 

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		$sql = "
			(
				SELECT status, COUNT(tipo_id) AS qtd FROM tddser  
				WHERE id_admin = :id_admin GROUP BY status )
	  		UNION ALL
				( SELECT 'vencido' AS status, COUNT(1) AS qtd FROM tddser 
					WHERE DATE_FORMAT(data_fim,'%Y-%m-%d') < DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') 
				AND  id_admin = :id_admin AND status <> 'finalizado'  
				GROUP BY 1 )
			UNION ALL
				( SELECT 'hoje' AS status, COUNT(1) AS qtd FROM tddser 
					WHERE DATE_FORMAT(data_fim,'%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') 
				AND  id_admin = :id_admin AND status <> 'finalizado'  
				GROUP BY 1 )	
				
			";

		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id_admin', $id_admin[0]);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}



	public static function getResumoEmpresaServicos($id_admin,$empresa_id){ 

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		if(!isset($empresa_id[0])){
			return json_encode(array('mensage'=>'Empresa não informada!','erro'=>true ));
		}

		$sql = "SELECT status, COUNT(tipo_id) AS qtd FROM tddser  WHERE id_admin = :id_admin AND empresa_id = ".$empresa_id." GROUP BY status";

		$sql = "
		(
			SELECT status, COUNT(tipo_id) AS qtd FROM tddser  
			WHERE id_admin = :id_admin GROUP BY status )
		  UNION ALL
			( SELECT 'vencidos' AS status, COUNT(1) AS qtd FROM tddser 
				WHERE DATE_FORMAT(data_fim,'%Y-%m-%d') < DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') 
			AND  id_admin = :id_admin AND status <> 'finalizado' AND empresa_id = ".$empresa_id." 
			GROUP BY 1 )
		UNION ALL
			( SELECT 'Para Hoje' AS status, COUNT(1) AS qtd FROM tddser 
				WHERE DATE_FORMAT(data_fim,'%Y-%m-%d') = DATE_FORMAT(CURRENT_DATE,'%Y-%m-%d') 
			AND  id_admin = :id_admin AND status <> 'finalizado'  AND empresa_id = ".$empresa_id." 
			GROUP BY 1 )	
			
		";

		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id_admin', $id_admin[0]);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}



	public static function getIdServicos($id_admin,$id){ 
	    
	    $data = array();
        $rest  = json_encode($data);
        $imagensArray = [];
        $informacaoArray = [];

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		$stmt = DB::prepare("SELECT ser.* , emp.nome as empresa_nome , tip.descricao as tipo_descricao
                        		    FROM tddser ser 
                        		    inner join tddemp emp on emp.id = ser.empresa_id and emp.id_admin = ser.id_admin 
                        		    inner join fomulario_tipo tip on tip.id = ser.tipo_id and tip.id_admin = ser.id_admin 
                        		WHERE ser.id = :id AND ser.id_admin = :id_admin");
                        		
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':id_admin', $id_admin[0]);
		$stmt->execute();

		$resultados = $stmt->fetchAll();

		if(isset($resultados[0])){

    		$stmt2 = DB::prepare("SELECT * FROM tddser_img WHERE servico_id = :id");
    		$stmt2->bindParam(':id', $resultados[0]->id);
    		
    		if($stmt2->execute()){
    			
    			$resultado2 = $stmt2->fetchAll();
    			$imagensArray = [];
    
    			foreach($resultado2 as $row){
    				
    				
    				$imagemBlob = $row->imagem;
    
    				$imagemBase64 = base64_encode($imagemBlob);
    
    				$imagensArray[] = [	'id' => $row->id ,
    									'imagem_base64' => $imagemBase64,
    								    'descricao' => $row->descricao ];
    
    			}
    
    		}
			$resultados[0]->imagem = $imagensArray;
			
			
		    $stmt3 = DB::prepare("SELECT * FROM tddser_info WHERE servico_id = :id");
    		$stmt3->bindParam(':id', $resultados[0]->id);
    		
    		if($stmt3->execute()){
    			
    			$resultado3 = $stmt3->fetchAll();
    			$informacaoArray = [];
    
    			foreach($resultado3 as $row){
    
    				$informacaoArray[] = [	'id' => $row->id ,
    								    	'descricao' => $row->descricao,
											'created_at' => $row->created_at ];
    
    			}
    
    		}
    		$resultados[0]->informacao = $informacaoArray;
    		
		
			$rest = json_encode(  $resultados[0] , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 
		}

		return $rest;
		
	}

	public static function addServicos($data,$id_admin){ 
		try {

    		if(!isset($id_admin[0])){ 
    			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
    		}
    
    		// Verifica se todos os campos obrigatórios estão presentes
    		$camposObrigatorios = ['empresa_id','tipo_id','titulo','descricao','data_inicio','data_fim'];
    
    		foreach ($camposObrigatorios as $campo) {
    			if (!isset($data[$campo]) || empty($data[$campo])) {
    				return json_encode(array('mensagem'=>"O campo '$campo' é obrigatório.",'erro'=>true ));
    			}
    		}
    	
    		// Criação da instância da sua classe DB
    		$db = DB::getInstance();
    	
    		// Insere os dados no banco de dados
    		$sql = 'INSERT INTO tddser (  empresa_id ,  tipo_id,  titulo ,  descricao ,  data_inicio ,  data_fim,  id_admin)
    					        VALUES ( :empresa_id , :tipo_id, :titulo , :descricao , :data_inicio , :data_fim, :id_admin)';
    
    		$stmt = $db->prepare($sql);
    	
    		foreach ($camposObrigatorios as $campo) {
    			$stmt->bindParam(":$campo", $data[$campo]);
    		}
    
    		$stmt->bindParam(':id_admin', $id_admin[0] );
    	
    		// Executa a consulta
    		if ($stmt->execute()) {
    			return json_encode(array('mensagem'=>"Serviço cadastrado com sucesso!" ));
    		} else {
    			return json_encode(array('mensagem'=>"Falha ao incluir Serviço!",'erro'=>true ));
    		}
    
    	} catch (PDOException $e) {
    		return json_encode(array('mensagem'=>"Erro: ".$e->getMessage(),'erro'=>true ));
    	}	
	}

    public static function atenderServicos($id_admin,$id){ 

		if(!isset($id_admin[0])){ 
			$return =  json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}
		
	    if(!isset($return)){

			$atualizarUsuario = DB::prepare("UPDATE tddser SET status = 'andamento' WHERE id = :id");
			$atualizarUsuario->bindParam(':id', $id);
			
			if ($atualizarUsuario->execute()) {
				$return = json_encode(array('mensagem'=>"Serviço em andamento com sucesso!" ));
			} else {
				$return = json_encode(array('mensagem'=>"Falha Serviço em andamento",'erro'=>true ));
			}
		
		}
        
		return $return; 
        
    }

	public static function finalizarServicos($id_admin,$id){ 

		if(!isset($id_admin[0])){ 
			$return =  json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}
		
	    if(!isset($return)){

			$atualizarUsuario = DB::prepare("UPDATE tddser SET status = 'finalizado' WHERE id = :id");
			$atualizarUsuario->bindParam(':id', $id);
			
			if ($atualizarUsuario->execute()) {
				$return = json_encode(array('mensagem'=>"Serviço finalizado com sucesso!" ));
			} else {
				$return = json_encode(array('mensagem'=>"Falha Serviço finalizado",'erro'=>true ));
			}
		
		}
        
		return $return; 
        
    }

	// SELECT * FROM `tddser_img`
	// Perfil [ Editar em linha ] [ Editar ] [ Demonstrar SQL ] [ Criar código PHP ] [ Atualizar ]
	// id	servico_id	descricao	imagem
	public static function uploadImagem($img , $descricao, $id ){

		$db = DB::getInstance();

		// Insere os dados no banco de dados
		$sql = 'INSERT INTO tddser_img (  servico_id ,  descricao,  imagem )
		VALUES ( :servico_id , :descricao, :imagem )';

		$stmt = $db->prepare($sql);
	    
		$stmt->bindParam(':servico_id', $id);
		$stmt->bindParam(':descricao' , $descricao);
		$stmt->bindParam(':imagem'	  , $img);

		if ($stmt->execute()) {
			$return = json_encode(array('mensagem'=>"Imagem adicionada com sucesso!" ));
		} else {
			$return = json_encode(array('mensagem'=>"Falha ao incluir Imagem",'erro'=>true ));
		}
		
		return $return; 
	}

	public static function removerImagem($id_admin, $id ){

		if(!isset($id_admin[0])){ 
			$return =  json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		$db = DB::getInstance();
		$sql = 'DELETE FROM tddser_img WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);
		
		if ($stmt->execute()) {
			$return = json_encode(array('mensagem'=>"Imagem Excluida com sucesso!" ));
		} else {
			$return = json_encode(array('mensagem'=>"Falha Imagem não Excluida",'erro'=>true ));
		}
		
		return $return;
	}
	

	/*adicionar informações */
	public static function addInformacao($id_admin,$id,$data){
		try {

    		if(!isset($id_admin[0])){ 
    			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
    		}

			if (!isset($data['descricao']) || empty($data['descricao'])) {
				return json_encode(array('mensagem'=>"O campo descricao é obrigatório.",'erro'=>true ));
			}
    	
    		// Criação da instância da sua classe DB
    		$db = DB::getInstance();
    	
    		// Insere os dados no banco de dados
    		$sql = 'INSERT INTO tddser_info (  servico_id , descricao )
    					        VALUES ( :servico_id ,:descricao )';
    
    		$stmt = $db->prepare($sql);   
    		$stmt->bindParam(':servico_id', $id );
    		$stmt->bindParam(':descricao' , $data['descricao'] );
    	
    		// Executa a consulta
    		if ($stmt->execute()) {
    			return json_encode(array('mensagem'=>"Informacoes cadastrado com sucesso!" ));
    		} else {
    			return json_encode(array('mensagem'=>"Falha ao incluir Informacoes!",'erro'=>true ));
    		}
    
    	} catch (PDOException $e) {
    		return json_encode(array('mensagem'=>"Erro: ".$e->getMessage(),'erro'=>true ));
    	}	
		
	}

	public static function removerInformacao($id_admin, $id ){

		if(!isset($id_admin[0])){ 
			$return =  json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		$db = DB::getInstance();
		$sql = 'DELETE FROM tddser_info WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);
		
		if ($stmt->execute()) {
			$return = json_encode(array('mensagem'=>"Informacao Excluida com sucesso!" ));
		} else {
			$return = json_encode(array('mensagem'=>"Falha Informacao não Excluida",'erro'=>true ));
		}
		
		return $return;
	}


}