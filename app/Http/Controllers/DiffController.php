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

    /**
     * Compare two JSON objects and return the differences
     *
     * @param mixed $obj1 First object (array or object)
     * @param mixed $obj2 Second object (array or object)
     * @param string $path Current path for nested objects (used internally)
     * @return array Array of differences with paths and values
     */
    public function jsonDiff($obj1, $obj2, $path = '')
    {
        $diff = [];

        // Convert objects to arrays for easier comparison
        $arr1 = is_object($obj1) ? (array)$obj1 : $obj1;
        $arr2 = is_object($obj2) ? (array)$obj2 : $obj2;

        // Handle case where one is array/object and other is not
        if (is_array($arr1) !== is_array($arr2)) {
            $diff[] = [
                'path' => $path ?: 'root',
                'type' => 'type_change',
                'old_value' => $obj1,
                'new_value' => $obj2,
                'old_type' => gettype($obj1),
                'new_type' => gettype($obj2)
            ];
            return $diff;
        }

        // If both are not arrays, do direct comparison
        if (!is_array($arr1) && !is_array($arr2)) {
            if ($arr1 !== $arr2) {
                $diff[] = [
                    'path' => $path ?: 'root',
                    'type' => 'value_change',
                    'old_value' => $obj1,
                    'new_value' => $obj2
                ];
            }
            return $diff;
        }

        // Get all unique keys from both arrays
        $allKeys = array_unique(array_merge(array_keys($arr1), array_keys($arr2)));

        foreach ($allKeys as $key) {
            $currentPath = $path ? $path . '.' . $key : $key;

            // Key exists in first but not second (removed)
            if (array_key_exists($key, $arr1) && !array_key_exists($key, $arr2)) {
                $diff[] = [
                    'path' => $currentPath,
                    'type' => 'removed',
                    'old_value' => $arr1[$key],
                    'new_value' => null
                ];
            } // Key exists in second but not first (added)
            elseif (!array_key_exists($key, $arr1) && array_key_exists($key, $arr2)) {
                $diff[] = [
                    'path' => $currentPath,
                    'type' => 'added',
                    'old_value' => null,
                    'new_value' => $arr2[$key]
                ];
            } // Key exists in both, compare values recursively
            elseif (array_key_exists($key, $arr1) && array_key_exists($key, $arr2)) {
                $nestedDiff = $this->jsonDiff($arr1[$key], $arr2[$key], $currentPath);
                $diff = array_merge($diff, $nestedDiff);
            }
        }

        return $diff;
    }
}
