<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DiffController extends Controller
{
    public function index()
    {
        $payload1 = Cache::get('payload1');
        $payload2 = Cache::get('payload2');

        if ($payload1 == null && $payload2 == null) {
            return response()->json(['message' => 'two payloads are required to diff'], 400);
        }

        if ($payload1['id'] != $payload2['id']) {
            return response()->json(['message' => 'payloads are for different products'], 400);
        }

        $diff = [];

        // Check the metadata for changes
        $diff['title'] = $this->compare($payload1, $payload2, 'title');
        $diff['description'] = $this->compare($payload1, $payload2, 'description');

        // Check the images and variants for changes by ID
        if (isset($payload1['images']) && isset($payload2['images'])) {
            $diff['images'] = $this->compareArraysById($payload1['images'], $payload2['images']);
        }
        if (isset($payload1['variants']) && isset($payload2['variants'])) {
            $diff['variants'] = $this->compareArraysById($payload1['variants'], $payload2['variants']);
        }

        return response()->json($diff);
    }

    public function compareArraysById($arr1, $arr2)
    {
        $diff = [];

        if (!is_array($arr1) || !is_array($arr2)) {
            return $diff;
        }

        // get all the unique IDs for the objects in both arrays
        $arr1Ids = array_column($arr1, 'id');
        $arr2Ids = array_column($arr2, 'id');

        $uniqueIds = array_unique(array_merge($arr1Ids, $arr2Ids));

        foreach ($uniqueIds as $id) {
            $arr1Item = array_find($arr1, fn($i) => isset($i['id']) && $i['id'] == $id);
            $arr2Item = array_find($arr2, fn($i) => isset($i['id']) && $i['id'] == $id);

            $itemDiff = [];

            // get all uniques keys for both objects so we can compare for changes
            $allKeys = array_unique(array_merge(array_keys($arr1Item ?? []), array_keys($arr2Item ?? [])));

            foreach ($allKeys as $key) {
                /**
                 * Compare each key in the object
                 *
                 * This assumes the structure is consistent and there is
                 * no need to check for sub arrays and walk the tree recursively
                 */
                $itemDiff[$key] = $this->compare($arr1Item, $arr2Item, $key);
            }

            $itemDiff['id'] = $id;

            $diff[] = $itemDiff;
        }

        return $diff;
    }

    public function compare($arr1, $arr2, $key)
    {
        // if the first object exists but the second doesn't, it was removed
        if ($arr1 != null && $arr2 == null) {
            return 'removed';
        }

        // if the first object doesn't exist but the second does, it was added
        if ($arr1 == null && $arr2 != null) {
            return 'added';
        }

        // if both are null we are in an unknown state
        if ($arr1 == null && $arr2 == null) {
            return 'unknown';
        }

        // if both exist then we can compare the values for equality
        return $arr1[$key] == $arr2[$key] ? 'equal' : 'changed';
    }

    public function store(Request $request)
    {
        if (!$request->isJson()) {
            return response(['message' => 'Input must be json'], 400);
        }

        // Cache payload 1 if it doesn't exist otherwise cache as payload 2
        // Naive implementation of the caching for times sake
        if (!Cache::has('payload1')) {
            Cache::put('payload1', $request->json()->all(), 10);
        } else {
            Cache::put('payload2', $request->json()->all(), 10);
        }

        return response()->json(['message' => 'success']);

    }
}
