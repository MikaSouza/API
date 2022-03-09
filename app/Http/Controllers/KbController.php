<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @group Knowledge Base
 */
class KbController extends Controller
{
    public static $zohoTokenModel = \Marshmallow\ZohoDesk\Models\ZohoToken::class;

    protected function getAccessToken()
    {
        $token = self::$zohoTokenModel::firstOrFail();
        if ($token->isExpired()) {
            $token->refresh();
        }

        return $token->access_token;
    }

    /**
     * @authenticated
     */
    public function listArticlesByCategory($category_id)
    {
        $response = Http::withHeaders([
            'orgId' => env('ZOHO_ORG_ID'),
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken()
        ])->get(env('ZOHO_DESK_HOST') . '/articles?permission=REGISTEREDUSERS&status=Published&categoryId=' . $category_id . '&orgId=' . env('ZOHO_ORG_ID'));

        $articles = [];
        $data = $response->json();

        foreach ($data['data'] as $k => $a) {
            $articles[$k]['id']  = $a['id'];
            $articles[$k]['title']  = $a['title'];
        }


        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $articles,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }


    /**
     * @authenticated
     */
    public function categories()
    {
        $response = Http::withHeaders([
            'orgId' => env('ZOHO_ORG_ID'),
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken()
        ])->get(env('ZOHO_DESK_HOST') . '/kbRootCategories/486535000000239754/categoryTree');

        $data = $response->json();

        $categories = [];
        foreach ($data['children'] as $k => $c) {
            if ($c['status'] == 'SHOW_IN_HELPCENTER') {
                $categories[$k]['id'] = $c['id'];
                $categories[$k]['name'] = $c['name'];
                $categories[$k]['order'] = $c['order'];
            }
        }

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $categories,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }



    /**
     * @authenticated
     */
    public function articleDetail($article_id)
    {

        $response = Http::withHeaders([
            'orgId' => env('ZOHO_ORG_ID'),
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken()
        ])->get(env('ZOHO_DESK_HOST') . '/articles/' . $article_id);


        $data = $response->json();

        return response()->json(
            [
                'code' => 200,
                'success' => true,
                'data' => $data,
                'message' => 'Dados listados com sucesso'
            ],
            200
        );
    }
}
