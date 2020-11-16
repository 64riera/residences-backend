<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        try {
            $careers = Career::select('*');

            if ($request) {
                if (!empty($request->name)) {
                    $careers = $careers->where(
                        'name',
                        'LIKE',
                        $request->name
                    );
                }

                if (!empty($request->description)) {
                    $careers = $careers->where(
                        'description',
                        'LIKE',
                        $request->description
                    );
                }
            }

            $careers = $careers->orderBy(
                'name',
                'ASC'
            )->get();
        } catch (Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => []
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }


        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $careers
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
            'description' => 'required|string|min:1',
            'modalityId' => 'required|integer|min:1'
        ]);

        try {
            $career = new Career();
            $career->name = $request->name;
            $career->description = $request->description;
            if ($request->modalityId) {
                $career->modality_id = $request->modalityId;
            }
            $career->save();
        } catch (Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $e->getMessage()
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $career
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
