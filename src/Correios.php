<?php

namespace SavioVarsalle\LaravelCorreiosBasico;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Correios
{
    public $api;
    public function __construct()
    {
        $this->api = new Client();
    }

    public function calculaPrecoPrazo($data)
    {
        $response = $this->api->request('POST', "https://www2.correios.com.br/sistemas/precosPrazos/prazos.cfm", [
            'headers' => [
                'Content-Type'    => 'application/x-www-form-urlencoded',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'Accept-Encoding' => 'gzip, deflate, br, zstd',
                'Accept-Language' => 'en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7',
                'Origin'          => 'https://www2.correios.com.br',
                'Referer'         => 'https://www2.correios.com.br/sistemas/precosPrazos/',
            ],
            'form_params' => [
                'data'                  => data_get($data, 'dataPostagem'),
                'dataAtual'             => now()->format('d/m/Y'),
                'cepOrigem'             => data_get($data, 'cepOrigem', ''),
                'cepDestino'            => data_get($data, 'cepDestino', ''),
                'servico'               => 04510,
                'compararServico'       => 'on',
                'Selecao'               => '',
                'Formato'               => 1,
                'embalagem1'            => 'outraEmbalagem1',
                'embalagem2'            => '',
                'Altura'                => data_get($data, 'altura', ''),
                'Largura'               => data_get($data, 'largura', ''),
                'Comprimento'           => data_get($data, 'comprimento', ''),
                'Selecao1'              => '',
                'proCod_in_1'           => '',
                'nomeEmbalagemCaixa'    => '',
                'TipoEmbalagem1'        => '',
                'Selecao2'              => '',
                'proCod_in_2'           => '',
                'TipoEmbalagem2'        => '',
                'Selecao3'              => '',
                'proCod_in_3'           => '',
                'TipoEmbalagem3'        => '',
                'Selecao4'              => '',
                'proCod_in_4'           => '',
                'TipoEmbalagem4'        => '',
                'Selecao5'              => '',
                'proCod_in_5'           => '',
                'TipoEmbalagem5'        => '',
                'Selecao6'              => '',
                'proCod_in_6'           => '',
                'TipoEmbalagem6'        => '',
                'Selecao7'              => '',
                'proCod_in_7'           => '',
                'TipoEmbalagem7'        => '',
                'Selecao14'             => '',
                'proCod_in_14'          => '',
                'TipoEmbalagem14'       => '',
                'Selecao15'             => '',
                'proCod_in_15'          => '',
                'TipoEmbalagem15'       => '',
                'Selecao16'             => '',
                'proCod_in_16'          => '',
                'TipoEmbalagem16'       => '',
                'Selecao17'             => '',
                'proCod_in_17'          => '',
                'TipoEmbalagem17'       => '',
                'Selecao18'             => '',
                'proCod_in_18'          => '',
                'TipoEmbalagem18'       => '',
                'Selecao19'             => '',
                'proCod_in_19'          => '',
                'TipoEmbalagem19'       => '',
                'Selecao20'             => '',
                'proCod_in_20'          => '',
                'peso'                  => data_get($data, 'peso', ''),
                'Selecao8'              => '',
                'proCod_in_8'           => '',
                'nomeEmbalagemEnvelope' => '',
                'TipoEmbalagem8'        => '',
                'Selecao9'              => '',
                'proCod_in_9'           => '',
                'TipoEmbalagem9'        => '',
                'Selecao10'             => '',
                'proCod_in_10'          => '',
                'Selecao11'             => '',
                'proCod_in_11'          => '',
                'Selecao12'             => '',
                'proCod_in_12'          => '',
                'TipoEmbalagem12'       => '',
                'Selecao13'             => '',
                'proCod_in_13'          => '',
                'TipoEmbalagem13'       => '',
                'Selecao21'             => '',
                'proCod_in_21'          => '',
                'TipoEmbalagem21'       => '',
                'Selecao22'             => '',
                'proCod_in_22'          => '',
                'TipoEmbalagem22'       => '',
                'Selecao23'             => '',
                'proCod_in_23'          => '',
                'TipoEmbalagem23'       => '',
                'Selecao24'             => '',
                'proCod_in_24'          => '',
                'TipoEmbalagem24'       => '',
                'Selecao25'             => '',
                'proCod_in_25'          => '',
                'TipoEmbalagem25'       => '',
                'Selecao26'             => '',
                'proCod_in_26'          => '',
                'Selecao27'             => '',
                'proCod_in_27'          => '',
                'Selecao28'             => '',
                'proCod_in_28'          => '',
                'TipoEmbalagem28'       => '',
                'Selecao29'             => '',
                'proCod_in_29'          => '',
                'TipoEmbalagem29'       => '',
                'Selecao30'             => '',
                'proCod_in_30'          => '',
                'TipoEmbalagem30'       => '',
                'valorDeclarado'        => '',
                'Calcular'              => 'Calcular',
            ],
        ]);

        $htmlContent = $response->getBody()->getContents();

        $crawler = new Crawler($htmlContent);

        $table = $crawler->filter('table.comparaResult');

        $data = [];

        $headers = $table->filter('tr.dragDropSection td')->each(function (Crawler $node) {
            return trim($node->text());
        });

        $rows = $table->filter('tr')->each(function (Crawler $node, $i) use ($headers) {
            $row = [];

            $node->filter('td')->each(function (Crawler $subNode, $j) use (&$row, $headers) {
                if (isset($headers[$j])) {
                    $row[$headers[$j]] = trim($subNode->text());
                }
            });

            return $row;
        });

        foreach ($rows as $row) {
            if (!empty($row)) {
                $data[] = $row;
            }
        }

        $names = $data[0];

        $result = [];

        foreach ($names as $key => $name) {
            if ($key === "") {
                continue;
            } // Ignorar valores vazios

            if (isset($data[1][$key])) {
                $dias  = null;
                $prazo = $data[1][$key];

                if ($prazo === '-') {
                    $prazo = null;
                } else {
                    $dias = explode(' ', $prazo)[4] + 1;
                }
            }

            if (isset($data[2][$key])) {
                $entrega = $data[2][$key];

                if ($entrega === '-') {
                    $entrega = null;
                }
            }

            if (isset($data[3][$key])) {
                $preco = $data[3][$key];
                $valor = null;

                if ($preco === '-') {
                    $preco = null;
                } else {
                    $valor  = explode(' ', $preco)[1];
                    $valor  = explode(',', $valor);
                    $valor1 = $valor[0];
                    $valor2 = $valor[1];
                    $valor  = $valor1 . '.' . $valor2;
                }
            }

            $result[$name] = [
                'servico' => $data[0][$key] ?? null,
                'dias'    => isset($data[1][$key]) ? $dias : null,
                'prazo'   => isset($data[1][$key]) ? $prazo : null,
                'entrega' => isset($data[2][$key]) ? $entrega : null,
                'preco'   => isset($data[3][$key]) ? $preco : null,
                'valor'   => isset($data[3][$key]) ? (float) $valor : null,
            ];
        }

        return $result;
    }
}
