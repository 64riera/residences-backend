<?php

namespace App\Http\Controllers;

use App\Models\UserStep;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserStepController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer|min:1|exists:users,id',
            'stepId' => 'required|integer|min:1|exists:steps,id',
            'textContent' => 'string',
            'fileUrl' => 'string',
            'fileName' => 'string',
            'status' => 'required|integer'
        ]);

        try {
            $userStep = new UserStep();
            $userStep->user_id = $request->userId;
            $userStep->step_id = $request->stepId;
    
            if (!empty($request->textContent)) {
                $userStep->text_content = $request->textContent;
            }
    
            if (!empty($request->fileUrl)) {
                $userStep->file_url = $request->fileUrl;
            }
    
            if (!empty($request->fileName)) {
                $userStep->file_name = $request->fileName;
            }
    
            $userStep->status = $request->status;
            $userStep->save();
        } catch (Exception $e) {
            return response()->json([
                'code' => Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'),
                'message' => $e->getMessage(),
                'data' => []
            ], Config::get('constants.responses.INTERNAL_SERVER_ERROR_CODE'));
        }

        return response()->json([
            'code' => Config::get('constants.responses.SUCCESS_CODE'),
            'message' => Config::get('constants.responses.SUCCESS_MESSAGE'),
            'data' => $userStep
        ], Config::get('constants.responses.SUCCESS_CODE'));
    }
}
