<?php 
namespace App\TipoServicos;

use App\DB\DB;

class TipoServicos {

	public static function getAllTipoServicos($id_admin){ 
		$addSql = "";
		$addSql1 = "";

		if(!isset($id_admin[0])){ 
			return json_encode(array('mensage'=>'Impossivel adicionar usuario comum','erro'=>true ));
		}

		$sql = "SELECT * FROM fomulario_tipo WHERE id_admin = :id_admin AND ativo = 1 ";

		// var_dump($sql);

		$stmt = DB::prepare($sql);
		$stmt->bindParam(':id_admin', $id_admin[0]);
        $stmt->execute();
 
        $resultados = $stmt->fetchAll(); 
        $rest = json_encode( $resultados , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 

		return $rest;
	}

}