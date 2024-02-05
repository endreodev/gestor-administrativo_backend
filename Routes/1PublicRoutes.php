<?php

use App\Auth\Auth;

//Home para auxiliar usuario
$app->get('/', function ($request,$response, $args) {
	$valide = array('msg'=>'Necessario Realizar o Cadastro na tomaker.com.br');
	$rest = json_encode( $valide  );
	$response->getBody()->write( $rest );
	$response->withHeader('Content-Type', 'application/json'); 
	return $response;
});

$app->post('/api/login', function ($request, $response, $args)  {
	try{
	    
		$data = json_decode(file_get_contents("php://input"), true); 
		$rest = Auth::login($data);
		$response->getBody()->write( $rest );
		$newResponse = $response->withHeader('Content-type', 'application/json');
		return $newResponse;
		
	}catch (Exception $e) {
	    
		// var_dump($e->getMessage());
		$return = array('mensagem'=>'OCOREU UM ERRO NO SISTEMA:'.$e->getMessage(),'erro'=>true );
		$return = json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 
		$response->getBody()->write( $return );
		$response->withHeader('Content-Type', 'application/json'); 
		return $response;
		
	}
});