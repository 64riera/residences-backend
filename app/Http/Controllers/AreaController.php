<?php

namespace App\Http\Controllers;

use App\Models\AdminArea;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        try {
            $areas = AdminArea::select('*');
    
            if ($request) {
                if (!empty($request->name)) {
                    $areas = $areas->where(
                        'name',
                        'LIKE',
                        $request->name
                    );
                }
    
                if (!empty($request->description)) {
                    $areas = $areas->where(
                        'description',
                        'LIKE',
                        $request->description
                    );
                }
            }
    
            $areas = $areas->orderBy(
                'name',
                'ASC'
            )->get();
        } catch (Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' >= $e->getMessage()
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }


        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $areas
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Creates the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:1',
            'description' => 'required|string|min:1'
        ]);

        try {
            $area = new AdminArea();
            $area->name = $request->name;
            $area->description = $request->description;
            $area->save();
        } catch (Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' >= $e->getMessage()
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $area
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
