<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ProcessController extends Controller
{
    /**
     * Get all the processes storaged
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'description' => 'string',
            'for_everyone' => 'boolean',
            'is_active' => 'boolean'
        ]);

        try {
            $processes = Process::select(
                'id',
                'name',
                'description',
                'for_everyone',
                'is_active'
            );

            if ($request) {
                if ($request->name) {
                    $processes = $processes->where(
                        'name',
                        'LIKE',
                        '%' . $request->name . '%'
                    );
                }

                if ($request->description) {
                    $processes = $processes->where(
                        'description',
                        'LIKE',
                        '%' . $request->description . '%'
                    );
                }

                if ($request->forEveryone) {
                    $processes = $processes->where(
                        'for_everyone',
                        $request->forEveryone
                    );
                }

                if ($request->isActive) {
                    $processes = $processes->where(
                        'is_active',
                        $request->isActive
                    );
                }
            }

            $processes = $processes->orderBy(
                'id',
                'DESC'
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
            'data' => $processes
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Create a new process.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'forEveryone' => 'boolean',
            'isActive' => 'boolean'
        ]);

        try {
            $process = new Process;
            $process->name = $request->name;
            $process->description = $request->description;

            if (!empty($request->forEveryone)) {
                $process->for_everyone = $request->forEveryone;
            }

            if (!empty($request->isActive)) {
                $process->is_active = $request->isActive;
            }

            $process->save();
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
            'data' => $process
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Update the specified process in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1|exists:processes,id',
            'name' => 'string|min:1',
            'description' => 'string|min:1',
            'forEveryone' => 'boolean',
            'isActive' => 'boolean'
        ]);

        try {
            $process = Process::find($request->id);
            $process->name = $request->name ? $request->name : $process->name;
            $process->description = $request->description ? $request->description : $process->description;
            $process->for_everyone = $request->forEveryone ? $request->forEveryone : $process->for_everyone;
            $process->is_active = $request->isActive ? $request->isActive : $process->is_active;
            $process->save();
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
            'data' => $process
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id],[
            'id' => 'required|integer|min:1|exists:processes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Config::get('constants.responses.FAIL_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $validator->errors()
            ], Config::get('constants.responses.SUCCESS_CODE'));
        }

        try {
            Process::destroy($id);
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
