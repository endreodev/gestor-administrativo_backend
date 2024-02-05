<?php

    use App\AuthBeforeMiddleware\AuthBeforeMiddleware;
    use App\TipoServicos\TipoServicos;

    $app->get('/api/tiposervicos', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $rest = TipoServicos::getAllTipoServicos($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());
