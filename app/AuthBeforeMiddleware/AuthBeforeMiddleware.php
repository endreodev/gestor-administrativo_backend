<?php

namespace App\AuthBeforeMiddleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Auth\Auth;

class AuthBeforeMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  Request        $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */

	public function __invoke($request, $handler){

        $token = $request->getHeader('token');
        if(!Auth::validarLogin($token)) {

            $response = new Response();
			$return = array('mensagem'=>'Token Invalido ou Vazio [token]','erro'=>true );
			$return = json_encode( $return , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES ); 
            $response->getBody()->write( $return );
			$response = $response->withHeader('Content-type', 'application/json');
            $response = $response->withStatus(302);

            return $response;

        }
        $response = $handler->handle($request);
        return $response;
    }
}
