<?php

namespace App\Http\Controllers;

use App\Models\AdminArea;
use App\Models\Career;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::select(
                'name',
                'last_name',
                'control_number',
                'email',
                'area_id',
                'birthdate',
                'address',
                'is_active',
                'in_residences',
                'phone',
                'visible_mail',
                'visible_phone',
                'user_type',
                'created_at',
                'updated_at'
            );

            if ($request) {
                if (!empty($request->name)) {
                    $users = $users->where(
                        'name',
                        'LIKE',
                        '%' . $request->name . '%'
                    );
                }

                if (!empty($request->lastName)) {
                    $users = $users->where(
                        'last_name',
                        'LIKE',
                        '%' . $request->lastName . '%'
                    );
                }

                if (!empty($request->controlNumber)) {
                    $users = $users->where(
                        'control_number',
                        'LIKE',
                        '%' . $request->controlNumber . '%'
                    );
                }

                if (!empty($request->email)) {
                    $users = $users->where(
                        'email',
                        'LIKE',
                        '%' . $request->email . '%'
                    );
                }

                if (!empty($request->address)) {
                    $users = $users->where(
                        'address',
                        'LIKE',
                        '%' . $request->address . '%'
                    );
                }

                if (!empty($request->phone)) {
                    $users = $users->where(
                        'phone',
                        'LIKE',
                        '%' . $request->phone . '%'
                    );
                }
            }

            $users = $users->orderBy(
                'name',
                'ASC'
            )->get();

            foreach ($users as $user) {
                $user = self::validateDataAndArea($user);
            }
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
            'data' => $users
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }


    /**
     * Get data of specific user given an id
     *
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Config::get('constants.responses.FAIL_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $validator->errors()
            ], Config::get('constants.responses.FAIL_CODE'));
        }

        try {
            $user = User::find($id);
            $user = self::validateDataAndArea($user);
        } catch(Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $e->getMessage()
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $user
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Validates the visible data and area of a user
     *
     * @param  object $user
     * @return \Illuminate\Http\Response
     */
    public static function validateDataAndArea($user)
    {
        // Validates if a user wanna show its data
        if (!$user->visible_mail) {
            unset($user->email);
        }

        if (!$user->visible_phone) {
            unset($user->phone);
        }

        // Validates user type
        if ($user->user_type == Config::get('constants.codes.ADMIN')) {
            $user->area = AdminArea::find($user->area_id);
        } else if ($user->user_type == Config::get('constants.codes.ADMIN')) {
            $user->area = Career::find($user->area_id);
        }

        return $user;
    }
}
