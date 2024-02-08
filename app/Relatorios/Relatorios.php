<?php

    namespace App\Relatorios;

    use TCPDF;

    class Relatorios {

        public static function printPdf2($jsonData){
            // composer require mpdf/mpdf
            // Crie uma instância do mPDF
            $mpdf = new \Mpdf\Mpdf();

            $dados = json_decode($jsonData);

            ini_set('pcre.backtrack_limit', '10000000'); // Aumenta o limite para 10 milhões
            // Agora você pode chamar WriteHTML() com seu HTML grande
            // $mpdf->WriteHTML($seu_html_grande);

            // Observações e imagens - Exemplo estático
            $htmlContent = "
            <style>
                body {
                    font-family: 'calibri', sans-serif;
                }

                .header-container {
                    display: flex;
                    align-items: center;
                    margin-bottom: 20px;
                }

                .header-logo {
                    margin-right: 20px;
                }

                .header-info {
                    display: flex;
                    flex-direction: column;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    border: 1px solid black;
                    padding: 8px;
                    text-align: left;
                }

                th {
                    background-color: #f2f2f2;
                }

                /* Copie aqui as regras CSS específicas do Materialize que você precisa */
                .card {
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    margin: 10px;
                    padding: 20px;
                    display: flex;
                }

                .btn {
                    background-color: #ee6e73;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 2px;
                }

                /* Adicione mais estilizações conforme necessário */

                .rown {
                    display: flex;
                    // flex-wrap: wrap;
                    // margin-right: -4px;
                    // margin-left: -4px;
                }
                .column {
                    flex: 33.33%;
                    padding: 5px;
                  }

                .col {
                    flex-basis: 0;
                    flex-grow: 1;
                    max-width: 100%;
                    padding: 0 4px;
                    box-sizing: border-box;
                }
                .col-6 {
                    flex: 0 0 50%;
                    max-width: 50%;
                }
                .image-container img {
                    width: 100%;
                    height: auto;
                }
                p {
                    padding-left: 8px;
                }

            </style>";

            $htmlContent .= '
            
            <div class="row">

                <div class="col s12 l12">
                    <div class="card col s12">
                        <div class="card-content">
                        
                    <div class="header-info">
  
                         <div class="header-info">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>'.$dados->id.'</th>
                                        <th>Relaório de Serviços</th>
                                        <th>Emissão</th>
                                        <th>'.date('d-m-Y').'</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    
                    <br/>
                    
                            <div class="container">
                                <div class="header-container">

                                    <table>
                                        <tr> 
                                            <th rowspan="5" scope="col">
                                                <img src="assets/img/logoempresa.png" alt="Logo" width="100px">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col" >Empresa:</th>
                                            <th scope="col" colspan="3">SAFETY SINDICOS PROFISSIONAIS</th> 
                                        </tr>
                                        <tr>
                                            <th scope="row">Endereço:</th>
                                            <td colspan="3">
                                                Av. Historiador Rubens de Mendonça, 1856 <br>(sala 605) - Bosque da Saude, Cuiabá - MT, 78050-000
                                                <br>Edifício Cuiabá Office Tower
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th scope="row">Cidade:</th>
                                            <td>Cuiabá</td>
                                            <th>Estado:</th>
                                            <td>MT</td>
                                        </tr>
                                        <tr>
                                            <th>Contato</th>
                                            <td>(65) 9 8121-2085</td>
                                            <th>Email:</th>
                                            <td>contato@safetysindicos.com.br</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col s12 l12">
                    <div class="card col s12">
                        <div class="card-content">

                            <div class="header-info">

                                <table>
                                    <tbody style="text-align: left;">
                                        
                                        <tr>
                                            <th>Cliente:</th>
                                            <td colspan="3">'.$dados->empresa_nome.'</td>
                                            <th>Tipo de Serviço:</th>
                                            <td>'.$dados->tipo_descricao.'</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Título:</th>
                                            <td colspan="5">'.$dados->titulo.'</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Descrição:</th>
                                            <td colspan="5">'.$dados->descricao.'</td>
                                        </tr>
                                        
                                        <tr>
                                            <th>Data Inicial</th>
                                            <td>'.date("d/m/Y", strtotime($dados->data_inicio)).'</td>
                                            <th>Data Final</th>
                                            <td>'.date("d/m/Y", strtotime($dados->data_fim)).'</td>
                                            <th>Data Conclusão</th>
                                            <td>'.date("d/m/Y", strtotime($dados->updated_at)).'</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col s12 l12">
            <div class="card col s12">
                <div class="card-content">

                    <div class="header-info">
                        <div class="card-content" style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="card-title">Observação</span>
                        </div>
                         <div class="header-info">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Observações</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>

                                <tbody>';

                                foreach($dados->informacao as $valor ){
                                    $htmlContent .= ' 
                                    <tr>
                                        <td>'.$valor->id.'</td>
                                        <td>'.$valor->descricao.'</td>
                                        <td>'.date("d/m/Y", strtotime($valor->created_at)).'</td>
                                    </tr>';
                                }
                
                                $htmlContent .= '            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
                </div>


                <div class="col s12 l12">
                <div class="card col s12">
                    <div class="card-content">
                        <div class="header-info">';
                        
                        if (!empty($dados->imagem)) {
                            // Contador para verificar cada grupo de dois nomes
                            $contador = 0;

                            foreach ($dados->imagem as $objeto) {
                                // Para cada início de um novo par de nomes, abre uma nova tabela
                                if ($contador % 2 == 0) {
                                    if ($contador > 0) {
                                        // Fecha a tabela anterior, exceto na primeira vez
                                        $htmlContent .=  "</tbody></table>";
                                    }
                                    $htmlContent .=  "<table border='1' style='margin-right: 20px;'><tbody>";
                                }
                                
                                // Coloca o nome na célula da tabela
                                // Se for o primeiro nome do par, abre uma nova linha
                                if ($contador % 2 == 0) {
                                    $htmlContent .=  '<tr>
                                                        <td>
                                                            <img src="data:image/*;base64,'.$objeto->imagem_base64.'" width="250px" height="250px"/>
                                                            <p>'.$objeto->descricao.'</p>
                                                        </td>';
                                } else {
                                    // Se for o segundo nome do par, coloca na segunda coluna e fecha a linha
                                    $htmlContent .=  '  <td>
                                                            <img src="data:image/*;base64,'.$objeto->imagem_base64.'" width="250px" height="250px"/>
                                                            <p>'.$objeto->descricao.'</p>
                                                        </td>
                                                    </tr>';
                                }
                                
                                $contador++;
                            }

                            // Fecha a última tabela se a quantidade de nomes for ímpar
                            if ($contador % 2 != 0) {
                                $htmlContent .=  "<td></td></tr>";
                            }

                            $htmlContent .=  "</tbody></table>";
                            
                        }
                            
                        $htmlContent .= ' 
                       
                        </div>
                    </div>
                </div>
            </div>';
            // Escreva o conteúdo HTML no PDF
            $mpdf->WriteHTML($htmlContent);

            // Saída do PDF para o navegador (I para abrir no navegador, D para forçar o download)
            $pdf = $mpdf->Output('relatorio_servico.pdf', 'S');
            $pdfbase64 = base64_encode($pdf);              
            return json_encode(array('relatorio'=> $pdfbase64 ));

        }
 

    }