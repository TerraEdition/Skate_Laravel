<?php

namespace App\Http\Controllers\API;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function search(Request $request)
    {

        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'team' => 'nullable',
            ], [], [
                'team' => 'Tim',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return Response::make(400, $validator->errors());
            }
            return Response::make(200, ['status' => "OKE"]);
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
}
