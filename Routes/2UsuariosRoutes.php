<?php

    use App\AuthBeforeMiddleware\AuthBeforeMiddleware;
    use App\Usuarios\Usuarios;
 
    ######## USUARIOS  
    $app->get('/api/usuarios', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $rest = Usuarios::getAllUsuauios($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    $app->get('/api/usuarios/{id}', function ($request, $response, $args) {
        $id = $args['id'];
        $rest = Usuarios::getIdUsuauios($id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    // Endpoint para cadastrar usuário
    $app->post('/api/usuarios', function ($request, $response, $args) {
        $data = json_decode(file_get_contents("php://input"), true); 
        // $data = $request->getParsedBody();
        $rest = Usuarios::addUsuauios($data);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    // Endpoint para editar usuário
    $app->post('/api/usuarios/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $data = json_decode(file_get_contents("php://input"), true); 
        // $data = $request->getParsedBody();
        $rest = Usuarios::editarUsuauios($data,$id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    // // Endpoint para cadastrar usuário
    $app->post('/api/usuariosfilhos', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        // $data = $request->getParsedBody();
        $data = json_decode(file_get_contents("php://input"), true); 
        $rest = Usuarios::addUsuauiosFilhos($data,$id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

