<?php

namespace App\Http\Controllers;

use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Redis;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Part;
use Illuminate\Http\JsonResponse;

class PartController extends BaseController
{
    const COUNT_ROW_ON_PAGE = 2;

    /**
     * Action список частей рассказа
     *
     * @param int $page
     * @return JsonResponse
     */
    public function index($page = 1)
    {
        $query = Part::selected();
        $url = '/api/text/parts';

        return response()->json($this->createResponseForPaginator($query, $page, $url));
    }

    /**
     * Acion список частей на голосовании
     *
     * @param int $page
     * @return JsonResponse
     */
    public function vote($page = 1)
    {
        $query = Part::onVote()->where('number', Part::getLastPartNumber());
        $url = '/api/vote/parts';

        return response()->json($this->createResponseForPaginator($query, $page, $url, 'desc'));
    }

    /**
     * Формирование ответа для пагинации компонента vue.js
     *
     * @param $query
     * @param $page
     * @param $url
     * @param $sort
     *
     * @return array
     */
    private function createResponseForPaginator($query, $page, $url, $sort = 'art') : array
    {
        $count = ceil($query->count() / self::COUNT_ROW_ON_PAGE);

        $prev = $page - 1;
        $next = $page + 1;

        return [
            "current" => $page,
            "count"   => $count,
            "next"    => ($next <= $count) ? "$url/$next" : null,
            "prev"    => ($prev > 0) ? "$url/$prev" : null,
            "list"    => $query->orderBy('created_at', $sort)
                ->limit(self::COUNT_ROW_ON_PAGE)
                ->offset($prev * self::COUNT_ROW_ON_PAGE)
                ->get()
        ];
    }

    /**
     * Сохранение новой части
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @see AppServiceProvider
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'author' => 'required|min:4',
            'text' => 'required|min:20',
        ]);

        $part = Part::create($request->all());

        return response()->json($part);
    }

    /**
     * Обработка события когда ставят like
     *
     * @param $id
     * @return JsonResponse
     */
    public function like($id)
    {
        return response()->json(Part::addLike($id));
    }

    /**
     * Обработка события когда ставят dislike
     *
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id)
    {
        return response()->json(Part::addDislike($id));
    }

    /**
     * Выбираем часть в главный текст
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectPart(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:parts,id',
        ]);

        return response()->json(Part::selectPart($request->get('id')));
    }
}