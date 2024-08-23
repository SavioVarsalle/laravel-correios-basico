<?php

use SavioVarsalle\LaravelCorreiosBasico\Correios;

test('deve retornar o html com os dados do calculo.', function () {
    //dd(now()->format('d/m/Y'));
    $correios = new Correios();

    $retorno = $correios->calculaPrecoPrazo([
        'dataPostagem' => '27/08/2024',
        'cepOrigem'    => '36889-412',
        'cepDestino'   => '12302-022',
        'altura'       => 5.5,
        'largura'      => 8.2,
        'comprimento'  => 13.1,
        'peso'         => 0.5,
    ]);

    dd($retorno);
});
