<?php
    namespace App\Relatorios;

    use TCPDF;

    class Relatorios {

        public function printPdf($jsonData){

            // Decodifica o JSON para um objeto PHP
            $data = json_decode($jsonData);

            // Inicializa o TCPDF
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Safety Sindicos Profissionais');
            $pdf->SetTitle('Safety Sindicos Profissionais');
            // $pdf->SetSubject('TCPDF Tutorial');
            // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // set default header data
            // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('dejavusans', '', 10);

            // add a page
            $pdf->AddPage();

            $html = <<<EOF
            <h1>ID: {$data->id} - {$data->titulo}</h1>  
            <br>
            <h3>
                {$data->descricao} 
            </h3>
            <ul style="font-size:14pt;list-style-type:img|png|4|4|images/logo_example.png">
                <li>test custom bullet image</li>
                <li>test custom bullet image</li>
                <li>test custom bullet image</li>
                <li>test custom bullet image</li>
            <ul>
            EOF;

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');





            // Imprime a imagem
            if (!empty($data->imagem) && is_array($data->imagem)) {
                $pdf->Ln(10); // Adiciona uma linha
                foreach ($data->imagem as $img) {
                    $pdf->Image('@' . base64_decode($img->imagem_base64), '', '', 40, 40, 'PNG');
                    $pdf->Ln(50); // Espaço após a imagem
                }
            }

            // Imprime informações adicionais
            if (!empty($data->informacao) && is_array($data->informacao)) {
                foreach ($data->informacao as $info) {
                    $pdf->Write(0, $info->descricao, '', 0, 'L', true, 0, false, false, 0);
                }
            }

            // Fecha e exibe o arquivo PDF
            // $pdf->Output('relatorio.pdf', 'I');

            // Salva o PDF em uma string
            $pdfString = $pdf->Output('nome_do_arquivo.pdf', 'S');

            return  $pdfString;
            // Codifica o PDF em base64
            // $base64 = base64_encode($pdfString);
            // return $base64;

        
        }


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
                            <div class="container">
                                <div class="header-container">

                                    <table>
                                        <tr> 
                                            <th rowspan="5" scope="col">
                                                <img src="assets/img/logoempresa.png" alt="Logo" width="100px">
                                            </th>
                                        </tr>
                                        <tr> rowspan="3"
                                            <th scope="col">Empresa:</th>
                                            <th scope="col">SAFETY SINDICOS PROFISSIONAIS</th> 
                                        </tr>
                                        <tr>
                                            <th scope="row">Endereço:</th>
                                            <td>Complexo Empresarial Ataíde Ferreira da Silva - R.Nossa Sra. de Carmo, 46 - Sala 30 - Centro Norte</td> 
                                        </tr>
                                        <tr>
                                            <th scope="row">Cidade:</th>
                                            <td>Várzea Grande</td> 
                                        </tr>
                                        <tr>
                                            <th scope="row">Estado:</th>
                                            <td>MTEmpresa</td> 
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
                                            <td>'.$dados->data_inicio.'</td>
                                            <th>Data Final</th>
                                            <td>'.$dados->data_fim.'</td>
                                            <th>Data Conclusão</th>
                                            <td>'.$dados->updated_at.'</td>
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
                                        <td>'.$valor->created_at.'</td>
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
                                                        <img src="data:image/png;base64,'.$objeto->imagem_base64.'" width="300px" height="300px"/>
                                                        <p>'.$objeto->descricao.'</p>
                                                    </td>';
                            } else {
                                // Se for o segundo nome do par, coloca na segunda coluna e fecha a linha
                                $htmlContent .=  '  <td>
                                                        <img src="data:image/png;base64,'.$objeto->imagem_base64.'" width="300px" height="300px"/>
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
                        // $htmlContent .=  '</div>';

                            
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