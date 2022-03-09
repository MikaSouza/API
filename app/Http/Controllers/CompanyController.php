<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\models\Company;
use App\Http\Resources\CompanyResource;


/**
 * @group Empresas
 */
class CompanyController extends Controller
{
    /**
     * @authenticated
     */
    public function index()
    {
        $companies = Company::with(['users']);
        return CompanyResource::collection($companies->paginate(50))->response();
    }
    /**
     * @authenticated
     */

    public function store(Request $request)
    {
        //
    }

    /**
     * @authenticated
     */
    public function show($id)
    {
        //
    }

    /**
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @authenticated
     */
    public function destroy($id)
    {
        //
    }
}
