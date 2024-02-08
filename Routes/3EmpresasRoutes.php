<?php
    date_default_timezone_set('America/Cuiaba');
	use App\AuthBeforeMiddleware\AuthBeforeMiddleware; 
	use App\Empresas\Empresas;

	 ############################################
    ######## EMPRESAS 
    // Endpoint para consultar todos os usuários
    $app->get('/api/empresas', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $rest = Empresas::getAllEmpresas($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    // Endpoint para consultar todos os usuários
    $app->get('/api/empresas/{id}', function ($request, $response, $args) {
        $id = $args['id'];
        $rest = Empresas::getIdEmpresas($id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    // Endpoint para consultar todos os usuários
    $app->post('/api/empresas', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        // $data = $request->getParsedBody();
        $data = json_decode(file_get_contents("php://input"), true); 
        $rest = Empresas::addEmpresas($data,$id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());


    // Endpoint para consultar todos os usuários
    $app->post('/api/empresas/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $data = json_decode(file_get_contents("php://input"), true); 
        $rest = Empresas::editarEmpresas($data,$id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());