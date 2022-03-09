<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @group Billets
 */
class BilletsController extends Controller
{

    /**
     * @authenticated
     */
    public function index()
    {

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://service-boletos.ccmcloud.com.br/api/v1/billets/22504984000180');

        $response = json_decode($res->getBody());

        $billets = $response;

        // "id": 4094,
        // "num_fin": 729,
        // "cod_parc": 95,
        // "dt_negociacao": "2019-03-19",
        // "dt_vencimento": "2019-04-11",
        // "vlrbaixa": 21.81,
        // "historico": "REFERENTE A ABRIL/19 ",
        // "num_nota": 11977,
        // "nossonum": 27100027826,
        // "linha_digitavel": "23790.27101 90271.000276 82000.152007 9 78560000002181",
        // "rec_desp": 1,
        // "parceiro_razaosocial": "FZR PROPAGANDA E PUBLICIDADE LTDA",
        // "parceiro_cnpj": "22504984000180",

        $newBillets = [];
        foreach ($billets as $k=> $billet) {
            $newBillets[$k] = [
                'id' => $billet->id,
                // 'code' => $billet->code,
                'parceiro_cnpj' => $billet->parceiro_cnpj,
                'parceiro_razaosocial' => $billet->parceiro_razaosocial,
                'dt_negociacao' => $billet->dt_negociacao,
                'dt_vencimento' => $billet->dt_vencimento,
                'vlrbaixa' => $billet->vlrbaixa,
                'dt_vencimento' => $billet->dt_vencimento,
                'details' => [
                    // 'num_fin' => $billet->num_fin,
                    // 'cod_parc' => $billet->cod_parc,
                    // 'historico' => $billet->historico,
                    'num_nota' => $billet->num_nota,
                    // 'nossonum' => $billet->nossonum,
                    'linha_digitavel' => $billet->linha_digitavel,
                    // 'rec_desp' => $billet->rec_desp,
                ]
            ];
        }



        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $newBillets,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }
}
