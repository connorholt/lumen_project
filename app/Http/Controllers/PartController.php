<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
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
        return response()->json(Part::orderBy('created_at')->get());
    }

    public function vote()
    {
        return response()->json(Part::orderBy('created_at')->onVote()->get());
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

    public function like($id)
    {
        /** @var Model $part */
        $part = Part::find($id);
        $part->like_count += 1;
        $part->save();

        return response()->json($part);
    }

    public function dislike($id)
    {
        $part = Part::find($id);
        $part->dislike_count += 1;
        $part->save();

        return response()->json($part);
    }
}