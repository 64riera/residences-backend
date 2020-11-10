<?php

namespace App\Http\Controllers;

use App\Models\Step;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class StepController extends Controller
{
    /**
     * Get specific step
     *
     * @param  Integer  $stepId
     * @return \Illuminate\Http\Response
     */
    public function getOne($stepId)
    {
        $validator = Validator::make(['stepId' => $stepId], [
            'stepId' => 'required|integer|min:1|exists:steps,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => Config::get('constants.responses.FAIL_CODE'),
                'message' => Config::get('constants.responses.FAIL_MESSAGE'),
                'data' => $validator->errors()
            ], Config::get('constants.responses.FAIL_CODE'));
        }

        try {
            $step = Step::find($stepId);
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
            'data' => $step
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }

    /**
     * Updates specified step
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOne(Request $request)
    {
        $request->validate([
            'stepId' => 'required|integer|min:1|exists:steps,id',
            'title' => 'string|min:1',
            'description' => 'string|min:1',
            'instructions' => 'string|min:1',
            'includesFile' => 'boolean',
            'isActive' => 'boolean',
            'order' => 'integer|min:1'
        ]);

        try {
            $step = Step::find($request->stepId);
            $step->title = $request->title ? $request->title : $step->title;
            $step->description = $request->description ? $request->description : $step->description;
            $step->instructions = $request->instructions ? $request->instructions : $step->instructions;
            $step->includes_file = $request->includesFile ? $request->includesFile : $step->includes_file;
            $step->is_active = $request->isActive ? $request->isActive : $step->is_active;
            $step->order = $request->order ? $request->order : $step->order;
            $step->save();
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
            'data' => $step
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
