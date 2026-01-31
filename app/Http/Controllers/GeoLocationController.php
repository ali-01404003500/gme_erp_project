<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\GeoLocation;
use Illuminate\Http\Request;

class GeoLocationController extends Controller
{
    /**
     * Fetch geo-locations for TomSelect dropdown.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocations(Request $request)
    {

        $locations = GeoLocation::query();

        if ($request->filled('q')) {
            $locations = $locations->where('name', 'like', '%' . $request->input('q') . '%');
        }
        // ->where('type', $request->input('type'))
        if ($request->filled('type')) {
            $locations = $locations->where('type', $request->input('type'));
        }
        if ($request->filled('parent_id')) {
            $locations = $locations->where('parent_id', $request->input('parent_id'));
        }
        $result = $locations->limit(10)
            ->select('id', 'name as text')
            ->get();

        //if field with id
        if ($request->filled('id')) {
            $selected = GeoLocation::select('id', 'name as text')->find($request->input('id'));
            //add to top of $result
            $result = $result->prepend($selected);
        }

        return response()->json($result);
    }

    public function addGeoLocation(Request $request)
    {
        $geoLocation = new GeoLocation();
        $geoLocation->name = $request->input('name');
        $geoLocation->type = $request->input('type');
        $geoLocation->parent_id = $request->input('parent_id');
        $geoLocation->save();
        return response()->json($geoLocation);
    }
}
