
<?php

    require __DIR__ . '/vendor/autoload.php';

    date_default_timezone_set('America/Cuiaba');

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Factory\AppFactory;
    use App\Auth\Auth;
    use DI\Container;
    
    // $container = new Container();
    // AppFactory::setContainer($container);


    $app = AppFactory::create();
    $app->setBasePath("/gestoron");
    
    

    $app->add(new Tuupola\Middleware\CorsMiddleware([
        "origin" => ["*"],
        "Access-Control-Allow-Origin" => ["*"],
        "Control-Allow-Origin" => ["*"],
        "methods" => ["GET", "POST", "PATCH", "DELETE", "OPTIONS"],    
        "headers.allow" => ["Origin","token","id_admin", "Content-Type", "Authorization", "Accept", "ignoreLoadingBar", "X-Requested-With", "Access-Control-Allow-Origin"],
        "headers.expose" => [],
        "credentials" => true,
        "cache" => 0,        
    ]));


    // Inclua os arquivos de rota
    require_once __DIR__ . '/Routes/1PublicRoutes.php';
    require_once __DIR__ . '/Routes/2UsuariosRoutes.php';
    require_once __DIR__ . '/Routes/3EmpresasRoutes.php';
    require_once __DIR__ . '/Routes/4ServicosRoutes.php';
    require_once __DIR__ . '/Routes/5TipoServicosRoutes.php';

    
    
    //Inicializa app
    $app->run();
    

?>