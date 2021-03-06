<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\Step;
use App\Models\UserStep;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class ProcessController extends Controller
{

    /* CONSTANTS */
    const ACTIVE_STATUS = 1;
    const DONE_STATUS = 3;

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

            foreach ($processes as $process) {
                $process->is_editing = false;
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
            'data' => $processes
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    public function getOne($processId)
    {
        try {
            $process = Process::find($processId);
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
            'data' => $process
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
            'isActive' => 'required|boolean'
        ]);

        try {
            $process = Process::find($request->id);
            $process->name = $request->name ? $request->name : $process->name;
            $process->description = $request->description ? $request->description : $process->description;
            $process->for_everyone = $request->forEveryone ? $request->forEveryone : $process->for_everyone;
            $process->is_active = $request->isActive;
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

    /**
     * Add steps to a specified process
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSteps(Request $request)
    {
        $request->validate([
            'processId' => 'required|integer|min:1|exists:processes,id',
            'steps' => 'required|array',
            'steps.*.title' => 'required|string|min:1',
            'steps.*.description' => 'required|string|min:1',
            'steps.*.instructions' => 'required|string|min:1',
            'steps.*.includesFile' => 'required|boolean',
            'steps.*.isActive' => 'boolean',
            'steps.*.order' => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function() use ($request) {

                Step::where(
                    'process_id',
                    $request->processId
                )->delete();

                foreach ($request->steps as $reqStep) {
                    $step = new Step;
                    $step->title = $reqStep['title'];
                    $step->description = $reqStep['description'];
                    $step->instructions = $reqStep['instructions'];
                    $step->includes_file = $reqStep['includesFile'];
                    $step->is_active = !empty($reqStep['isActive']) ? $reqStep['isActive'] : self::ACTIVE_STATUS;
                    $step->order = $reqStep['order'];
                    $step->process_id = $request->processId;
                    $step->save();
                } 
            });

            $steps = Step::where(
                'process_id',
                $request->processId
            )->orderBy(
                'order',
                'ASC'
            )->get();
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
            'data' => $steps
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Get steps to a specified process
     *
     * @param  Integer  $processId
     * @return \Illuminate\Http\Response
     */
    public function getSteps($processId)
    {
        $validator = Validator::make(['processId' => $processId], [
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
            $steps = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->get();
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
            'data' => $steps
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Get steps to a specified process
     *
     * @param  Integer  $processId
     * @return \Illuminate\Http\Response
     */
    public function getStudentSteps(Request $request, $processId)
    {
        $validator = Validator::make(['processId' => $processId], [
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
            $steps = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->get();

            $totalStepsOfProcessCount = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->count();

            $idStepsOfProcess = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->pluck('id');

            $totalDoneUserStepsCount = UserStep::where(
                'user_id',
                $request->user()->id
            )->whereIn(
                'step_id',
                $idStepsOfProcess
            )->where(
                'status',
                self::DONE_STATUS
            )->count();

            $firstPendient = false;
            foreach ($steps as $step) {
                $tmpDoneStep = UserStep::where(
                    'user_id',
                    $request->user()->id
                )->where(
                    'step_id',
                    $step->id
                )->first();

                $step->currentProgress = $tmpDoneStep;
                if (empty($tmpDoneStep) && !$firstPendient) {
                    $step->blocked = false;
                    $firstPendient = true; 
                } else {
                    $step->blocked = true;
                }
            }

            $finalBody = new stdClass;
            $finalBody->steps = $steps;
            $finalBody->totalStepsOfProcess = $totalStepsOfProcessCount;
            $finalBody->doneStepsCount = $totalDoneUserStepsCount;
            $finalBody->percentageOfProgress = $totalStepsOfProcessCount > 0 && $totalDoneUserStepsCount > 0 ? ceil(($totalDoneUserStepsCount * 100) / $totalStepsOfProcessCount) : 0; 

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
            'data' => $finalBody
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Get steps to a specified process
     *
     * @param  Integer  $processId
     * @param  Integer  $studentId
     * @return \Illuminate\Http\Response
     */
    public function getStudentStepsForAdmin($processId, $studentId)
    {
        $validator = Validator::make(['processId' => $processId], [
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
            $steps = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->get();

            $totalStepsOfProcessCount = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->count();

            $idStepsOfProcess = Step::where(
                'process_id',
                $processId
            )->orderBy(
                'order',
                'ASC'
            )->pluck('id');

            $totalDoneUserStepsCount = UserStep::where(
                'user_id',
                $studentId
            )->whereIn(
                'step_id',
                $idStepsOfProcess
            )->where(
                'status',
                self::DONE_STATUS
            )->count();

            $firstPendient = false;
            foreach ($steps as $step) {
                $tmpDoneStep = UserStep::where(
                    'user_id',
                    $studentId
                )->where(
                    'step_id',
                    $step->id
                )->first();

                $step->currentProgress = $tmpDoneStep;
                if (empty($tmpDoneStep) && !$firstPendient) {
                    $step->blocked = false;
                    $firstPendient = true; 
                } else {
                    $step->blocked = true;
                }
            }

            $studentInfo = User::find($studentId);
            $studentInfo = UserController::validateDataAndArea($studentInfo);

            $finalBody = new stdClass;
            $finalBody->student = $studentInfo;
            $finalBody->steps = $steps;
            $finalBody->totalStepsOfProcess = $totalStepsOfProcessCount;
            $finalBody->doneStepsCount = $totalDoneUserStepsCount;
            $finalBody->percentageOfProgress = $totalStepsOfProcessCount > 0 && $totalDoneUserStepsCount > 0 ? ceil(($totalDoneUserStepsCount * 100) / $totalStepsOfProcessCount) : 0; 

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
            'data' => $finalBody
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Get steps to a specified process (Progress, tracking for admins)
     *
     * @param  Integer  $processId
     * @return \Illuminate\Http\Response
     */
    public static function getAdminSteps(Request $request, $processId)
    {
        $validator = Validator::make(['processId' => $processId], [
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
            $finalBody = new stdClass;
            $finalBody->students = [];
            $finalBody->process = null;
    
            $process = Process::find($processId);
            $totalCountSteps = Step::where(
                'process_id',
                $processId
            )->count();
    
            $stepsIdsOfProcess = Step::where(
                'process_id',
                $processId
            )->pluck('id');
    
            $usersWithStepsDoneIds = UserStep::whereIn(
                'step_id',
                $stepsIdsOfProcess
            )->where(
                'status',
                self::DONE_STATUS
            )->pluck('user_id');
    
            $usersDetails = User::whereIn(
                'id',
                $usersWithStepsDoneIds
            )->get();
    
            foreach ($usersDetails as $userDetail) {
                $userDetail = UserController::validateDataAndArea($usersDetails[0]);
                $userStepsCountDone = UserStep::where(
                    'user_id',
                    $userDetail->id
                )->count();
                $userDetail->stepsCountDone = $userStepsCountDone;
                $userDetail->percentageOfProgress = ceil( ($userStepsCountDone * 100) / $totalCountSteps );
            }
    
            $finalBody->process = $process;
            $finalBody->students = $usersDetails;
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
            'data' => $finalBody
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
