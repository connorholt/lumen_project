<?php

namespace App\Http\Controllers;

use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
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
    public function index($page = 1)
    {
        $count = ceil(Part::selected()->count() / 2);

        $prev = $page - 1;
        $next = $page + 1;

        return response()->json([
            "current" => $page,
            "count" => $count,
            "next" => ($next <= $count) ? "/api/text/parts/$next" : null,
            "prev" => ($prev > 0) ? "/api/text/parts/$prev" : null,
            "list" => Part::orderBy('created_at')->selected()->limit(2)->offset($prev * 2)->get()
        ]);
    }

    public function vote($page = 1)
    {
        $count = ceil(Part::where('number', Part::getLastPartNumber())->onVote()->count() / 2);

        $prev = $page - 1;
        $next = $page + 1;

        return response()->json([
            "current" => $page,
            "count" => $count,
            "next" => ($next <= $count) ? "/api/vote/parts/$next" : null,
            "prev" => ($prev > 0) ? "/api/vote/parts/$prev" : null,
            "list" => Part::orderBy('created_at', 'desc')->where('number', Part::getLastPartNumber())->limit(2)->offset($prev * 2)->get()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $part = Part::create($request->all());
        // @todo beforeSave
        $part->number = Part::getLastPartNumber();
        $part->save();

        $redis = Redis::connection();
        $redis->publish('message', json_encode($part, JSON_PRETTY_PRINT));
        
        return response()->json($part);
    }

    /**
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return response()->json(Part::destroy($id));
    }

    public function like($id)
    {
        return response()->json(Part::addLike($id));
    }

    public function dislike($id)
    {
        return response()->json(Part::addDislike($id));
    }

    public function selected(Request $request)
    {
        $part = Part::find($request->get('id'));

        if (!$part) {
            return response()->json(); // error
        }

        if ($part->number == Part::getLastPartNumber()) {
            return response()->json(); // error
        }

        $part->is_selected = true;
        if ($part->save()) {
            Part::incLastPartNumber();
        };

        return response()->json($part);
    }
}