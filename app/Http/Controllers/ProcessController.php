<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

            $processes->orderBy(
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
}
