<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Part;
use Illuminate\Http\JsonResponse;

class PartController extends BaseController
{
    /**
     * Получение всех частей рассказов
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Part::get());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json(Part::create($request->all()));
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return response()->json(Part::destroy($id));
    }
}