<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\InputBag;
use function Sodium\compare;

class DiffController extends Controller
{
    public function index()
    {
        $payload1 = Cache::get('payload1');
        $payload2 = Cache::get('payload2');

        if ($payload1 == null && $payload2 == null) {
            return response()->json('two payloads are required to diff', 400);
        }

        if ($payload1['id'] != $payload2['id']) {
            return response()->json('payloads are for different products', 400);
        }

        $diff = [];

        $diff['title'] = $this->compare($payload1, $payload2, 'title');
        $diff['description'] = $this->compare($payload1, $payload2, 'description');

        $diff['images'] = $this->compareArraysById($payload1['images'], $payload2['images']);
        $diff['variants'] = $this->compareArraysById($payload1['variants'], $payload2['variants']);

        return response()->json($diff);
    }

    public function compareArraysById($arr1, $arr2)
    {
        $arr1Ids = array_column($arr1, 'id');
        $arr2Ids = array_column($arr2, 'id');
        $uniqueIds = array_unique(array_merge($arr1Ids, $arr2Ids));

        $diff = [];

        foreach ($uniqueIds as $id) {
            $arr1Item = array_find($arr1, fn($i) => $i['id'] == $id);
            $arr2Item = array_find($arr2, fn($i) => $i['id'] == $id);

            $itemDiff = [];
            $allKeys = array_unique(array_merge(array_keys($arr1Item ?? []), array_keys($arr2Item ?? [])));

            foreach ($allKeys as $key) {
                $itemDiff[$key] = $this->compare($arr1Item, $arr2Item, $key);
            }

            $itemDiff['id'] = $id;

            $diff[] = $itemDiff;
        }

        return $diff;
    }

    public function compare($arr1, $arr2, $key)
    {
        if ($arr1 != null && $arr2 == null) {
            return 'removed';
        }

        if ($arr1 == null && $arr2 != null) {
            return 'added';
        }

        return $arr1[$key] == $arr2[$key] ? 'equal' : 'changed';
    }

    public function store(Request $request)
    {
        if (!$request->isJson()) {
            return response('Input must be json', 400);
        }

        if (!Cache::has('payload1')) {
            Cache::put('payload1', $request->json()->all(), 10);
        } else {
            Cache::put('payload2', $request->json()->all(), 10);
        }

        return response()->json('success');

    }
}
