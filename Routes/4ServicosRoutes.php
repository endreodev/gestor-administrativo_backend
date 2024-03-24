<?php
    date_default_timezone_set('America/Cuiaba');
    use App\AuthBeforeMiddleware\AuthBeforeMiddleware;
    use App\Servicos\Servicos;
    use App\Relatorios\Relatorios;
    
    //resumo todas os serviços resumidos
    $app->get('/api/servicos/resumo', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $rest = Servicos::getResumoServicos($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //todos os serviços resumidos por empresa
    $app->get('/api/servicos/resumo/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $rest = Servicos::getResumoEmpresaServicos($id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //serviços
    $app->get('/api/servicos', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $rest = Servicos::getAllServicos($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //serviço por id 
    $app->get('/api/servicos/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $rest = Servicos::getIdServicos($id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    
    //serviços / empresa / id 
    $app->get('/api/servicos/empresa/{idempresa}', function ($request, $response, $args) {
        $id_admin  = $request->getHeader('id_admin');
        $idempresa = $args['idempresa'];
        $rest = Servicos::getAllServicos($id_admin,$idempresa);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    $app->get('/api/servicos/{id}/{filtro}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $filtro = $args['filtro'];
        $rest = Servicos::getAllServicos($id_admin,$id,$filtro);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //ADICIONAR UM NOVO SERVIÇO
    $app->post('/api/servicos', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin'); 
        $data = json_decode(file_get_contents("php://input"), true);  
        $rest = Servicos::addServicos($data,$id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //atender serviço pelo id
    $app->get('/api/atender/servicos/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin'); 
        $id = $args['id'];
        $rest = Servicos::atenderServicos($id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;

    })->add(new AuthBeforeMiddleware());


    $app->get('/api/finalizar/servicos/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin'); 
        $id = $args['id'];
        $rest = Servicos::finalizarServicos($id_admin,$id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());


    //ADICIONAR IMAGEM AO ID DO SERVIÇO
    $app->post('/api/servicos/imagem/{id}', function ($request, $response, $args) {

        $id_admin = $request->getHeader('id_admin');  
        $input = $request->getParsedBody();
        $id = $args['id'];


        if (is_array($_FILES['imagem']['name'])) {
            $totalArquivos = count($_FILES['imagem']['name']);
            for ($i = 0; $i < $totalArquivos; $i++) {

                $imagemTmpName = $_FILES['imagem']['tmp_name'][$i];
                $imagemName = $_FILES['imagem']['name'][$i];
                
                if (is_uploaded_file($imagemTmpName)) {
                    $imagem = file_get_contents($imagemTmpName);
                    $descricao = $input['descricao'] ?? '';
                    // Salve os dados conforme necessário
                    $rest = Servicos::uploadImagem($imagem , $descricao , $id);
                }

            }

            $response->getBody()->write( $rest );
            $newResponse = $response->withHeader('Content-type', 'application/json');
            return $newResponse;

        } else {

            if (isset($_FILES['imagem'])) {
                $imagem = $_FILES['imagem']['tmp_name'];
                $nome = $_FILES['imagem']['name'];
            
                if (is_uploaded_file($imagem)) {
                    
                    $imagem = file_get_contents($imagem);
                    $descricao = $input['descricao'] ?? '';
                    // Salve os dados conforme necessário
                    $rest = Servicos::uploadImagem($imagem , $descricao , $id);
                    $response->getBody()->write( $rest );
                    $newResponse = $response->withHeader('Content-type', 'application/json');
                    return $newResponse;
    
                }
            }
        }

        $response->getBody()->write(array("mensagem" => "erro no envio das imagens"));
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;

    })->add(new AuthBeforeMiddleware());


    //ADICIONAR IMAGEM AO ID DO SERVIÇO
    $app->delete('/api/servicos/imagem/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $rest = Servicos::removerImagem($id_admin , $id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;

    })->add(new AuthBeforeMiddleware());
 

    //ADICIONAR IMAGEM AO ID DO SERVIÇO
    $app->post('/api/servicos/informacoes/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $data = json_decode(file_get_contents("php://input"), true);  
        $rest = Servicos::addInformacao($id_admin,$id,$data);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    })->add(new AuthBeforeMiddleware());

    //ADICIONAR IMAGEM AO ID DO SERVIÇO
    $app->delete('/api/servicos/informacoes/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $rest = Servicos::removerInformacao($id_admin , $id);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;

    })->add(new AuthBeforeMiddleware());
    
 
    $app->get('/api/sevicos/status', function ($request, $response, $args) {
        $id_admin   = $request->getHeader('id_admin');
        $rest       = Servicos::getStatusServicos($id_admin);
        $response->getBody()->write( $rest );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $newResponse;
    });


    //serviço por id 
    $app->get('/api/imprimir/servicos/{id}', function ($request, $response, $args) {
        $id_admin = $request->getHeader('id_admin');
        $id = $args['id'];
        $jsonData = Servicos::getIdServicos($id_admin,$id);
        // $relatorio = Relatorios::printPdf($jsonData);
        $relatorio = Relatorios::printPdf2($jsonData);
        $response->getBody()->write( $relatorio );
        $newResponse = $response->withHeader('Content-type', 'application/json');
        return $response;
    })->add(new AuthBeforeMiddleware());
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    