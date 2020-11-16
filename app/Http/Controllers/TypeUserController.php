<?php

namespace App\Http\Controllers;

use App\Models\TypeUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TypeUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        try {
            $typeUsers = TypeUser::select('*');
    
            if ($request) {
                if (!empty($request->name)) {
                    $typeUsers = $typeUsers->where(
                        'name',
                        'LIKE',
                        $request->name
                    );
                }
    
                if (!empty($request->description)) {
                    $typeUsers = $typeUsers->where(
                        'description',
                        'LIKE',
                        $request->description
                    );
                }
            }
    
            $typeUsers = $typeUsers->orderBy(
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
            'data' => $typeUsers
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
            $typeUser = new TypeUser();
            $typeUser->name = $request->name;
            $typeUser->description = $request->description;
            $typeUser->save();
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
            'data' => $typeUser
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
