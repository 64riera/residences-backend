<?php

namespace App\Http\Controllers;

use App\Models\ActiveUserProcess;
use App\Models\AdminArea;
use App\Models\Career;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /* CONSTANTS */
    const ACTIVE_VALUE = 1;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = User::select(
                'id',
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
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1|exists:users,id',
            'name' => 'string',
            'lastName' => 'string',
            'address' => 'string',
            'controlNumber' => 'numeric|unique:users,control_number',
            'areaId' => 'integer',
            'birthdate' => 'date',
            'isActive' => 'integer',
            'phone' => 'numeric',
            'visibleMail' => 'boolean',
            'visiblePhone' => 'boolean',
            'userType' => 'numeric|exists:type_users,id',
            'email' => 'string|email|unique:users',
            'password' => 'string'
        ]);

        try {
            $user = User::find($request->id);
            $user->name = $request->name ? $request->name : $user->name;
            $user->last_name = $request->lastName ? $request->lastName : $user->last_name;
            $user->address = $request->address ? $request->address : $user->address;
            $user->control_number = $request->controlNumber ? $request->controlNumber : $user->control_number;
            $user->area_id = $request->areaId ? $request->areaId : $user->area_id;
            $user->birthdate = $request->birthdate ? $request->birthdate : $user->birthdate;
            $user->is_active = $request->isActive ? $request->isActive : $user->is_active;
            $user->phone = $request->phone ? $request->phone : $user->phone;
            $user->visible_mail = $request->visibleMail ? $request->visibleMail : $user->visible_mail;
            $user->visible_phone = $request->visiblePhone ? $request->visiblePhone : $user->visible_phone;
            $user->user_type = $request->userType ? $request->userType : $user->user_type;
            $user->email = $request->email ? $request->email : $user->email;
            $user->password = $request->password ? bcrypt($request->password) : $user->password;
            $user->save();
            $user = self::validateDataAndArea($user);
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
            'data' => $user
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
            User::destroy($id);
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
            'data' => []
        ], Config::get('constants.responses.SUCCESS_CODE'));
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
        } else if ($user->user_type == Config::get('constants.codes.STUDENT')) {
            $user->area = Career::find($user->area_id);
        }

        return $user;
    }

    /**
     * Adds an active process to a user
     *
     * @param  object $user
     * @return \Illuminate\Http\Response
     */
    public function addActiveProcess(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer|min:1|exists:users,id',
            'processId' => 'required|integer|min:1|exists:processes,id'
        ]);

        $finalData = [];

        try {

            $registeredActiveProcess = ActiveUserProcess::where(
                'user_id',
                $request->userId
            )->where(
                'process_id',
                $request->processId
            )->first();

            // Verify if user has active that process
            if (!empty($registeredActiveProcess)) {
                // Verify if the registered process was disabled
                if (!$registeredActiveProcess->is_active) {
                    $registeredActiveProcess->is_active = self::ACTIVE_VALUE;
                    $registeredActiveProcess->save();
                    $finalData = $registeredActiveProcess;
                } else {
                    // The user already has that process
                    return response()->json([
                        'code' => Config::get('constants.responses.FAIL_CODE'),
                        'message' => "User already has that active process",
                        'data' => []
                    ], Config::get('constants.responses.FAIL_CODE'));
                }
            } else {
                $activeProcess = new ActiveUserProcess();
                $activeProcess->user_id = $request->userId;
                $activeProcess->process_id = $request->processId;
                $activeProcess->save();
                $finalData = $activeProcess;
            }

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
            'data' => $finalData
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Returns the active processes of a user
     *
     * @param  object $user
     * @return \Illuminate\Http\Response
     */
    public function getActiveProcesses($userId)
    {
        $validator = Validator::make(['idUser' => $userId], [
            'idUser' => 'required|integer|min:1|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Config::get('constants.responses.FAIL_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $validator->errors()
            ], Config::get('constants.responses.FAIL_CODE'));
        }

        try {
            $activeProcesses = ActiveUserProcess::select(
                'active_user_processes.*',
                'users.name as user_name',
                'users.last_name as user_last_name',
                'processes.name as process_name'
            )->where(
                'user_id',
                $userId
            )->leftJoin(
                'users',
                'users.id',
                '=',
                'active_user_processes.user_id'
            )->leftJoin(
                'processes',
                'processes.id',
                '=',
                'active_user_processes.process_id'
            )->orderBy(
                'active_user_processes.created_at',
                'ASC'
            )->get();
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
            'data' => $activeProcesses
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Deletes an active process of user
     *
     * @param  object $user
     * @return \Illuminate\Http\Response
     */
    public function deleteActiveProcess($userId, $processId)
    {
        $validator = Validator::make([
            'userId' => $userId,
            'processId' => $processId
        ], [
            'userId' => 'required|integer|min:1|exists:users,id',
            'processId' => 'required|integer|min:1|exists:processes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Config::get('constants.responses.FAIL_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $validator->errors()
            ], Config::get('constants.responses.FAIL_CODE'));
        }

        try {
            $registeredActiveProcess = ActiveUserProcess::where(
                'user_id',
                $userId
            )->where(
                'process_id',
                $processId
            )->first();

            if (empty($registeredActiveProcess)) {
                return response()->json([
                    'code' => Config::get('constants.responses.FAIL_CODE'),
                    'message' => "User hasn't that active process",
                    'data' => []
                ], Config::get('constants.responses.FAIL_CODE'));
            }

            $registeredActiveProcess->delete();
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
            'data' => []
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
