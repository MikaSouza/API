<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @group Investimentos
 */
class InvestController extends Controller
{

    /**
     * @authenticated
     */
    public function recommendWallets()
    {

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://ccm-potenza:100/contas');

        $response = json_decode($res->getBody());

        $wallets = $response->Data->Contas;

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $wallets,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }

    /**
     * @authenticated
     */
    public function recommend($wallet_id)
    {

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://ccm-potenza:100/recomenda/' . $wallet_id);

        //3549094
        $response = json_decode($res->getBody());
        // return $response->Data;exit;
        $wallet = $response->Data->Carteira;
        $client = $response->Data->Cliente;
        $recomendations = $response->Data->Recomendacoes;
        $balanceamento = $response->Data->Porcentagens->Balanceamento;
        $produtos = $response->Data->Porcentagens->Produtos;
        $porcentagens = $response->Data->Porcentagens->Porcentagens;
        $diferenca = $response->Data->Porcentagens->Diferenca;
        $portfolio = $response->Data->Portfolio;
        $alertas = $response->Data->Alertas;


        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => [
                    'wallet' => $wallet,
                    'client' => $client,
                    'recomendations' => $recomendations,
                    'balanceamento' => $balanceamento,
                    'produtos' => $produtos,
                    'porcentagens' => $porcentagens,
                    'diferenca' => $diferenca,
                    'portfolio' => $portfolio,
                    'alertas' => $alertas,
                ],
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }



    /**
     * @authenticated
     */
    public function markowitzProducts()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://ccm-markowitz:300/ativos');

        $response = json_decode($res->getBody());

        $products = $response->Data;

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $products,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }


    /**
     * @authenticated
     */
    public function markowitzCalc($products, $application, $volatility)
    {
        // to-do VALIDAR SE É POTENZA 
        // return auth()->user()->company_id;exit;
        // $products = $request->get('products');
        // $application = $request->get('application');
        // $volatility = $request->get('volatility');


        // $productsNew = trim(implode(", ", $products));

        // print_r($products);exit;


        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://ccm-markowitz:300/balanco/[' . $products . ']/' . $application . '/' . $volatility);

        $response = json_decode($res->getBody());
        // return $response->Data;exit;

        $balanceamento = $response->Data->Balanceamento;
        $portfolio = $response->Data->Portfolio;
        $valores = $response->Data->Valores;
        $alerta = $response->Data->Alerta;
        

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => [
                    'balanceamento' => $balanceamento,
                    'portfolio' => $portfolio,
                    'valores' => $valores,
                    'alerta' => $alerta,
                ],
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }
}
