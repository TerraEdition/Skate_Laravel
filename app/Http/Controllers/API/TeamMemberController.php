<?php

namespace App\Http\Controllers\API;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\TournamentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    public function search(Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'group_slug' => 'required',
                'team_slug' => 'required',
                'key' => 'nullable',
            ], [], [
                'group_slug' => 'Grup',
                'team_slug' => 'Tim',
                'key' => 'Kata Pencarian',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return Response::make(400, $validator->errors());
            }
            # check group exist
            $group = TournamentGroup::where('slug', $request->input('group_slug'))->first();
            # check if validation fails
            if (empty($group)) {
                return Response::make(400, __('global.group_not_found'));
            }
            $data = [
                'member' => TeamMember::get_member_by_rule_group($group, $request->input('team_slug')),
            ];
            return Response::make(200, __('global.success'), $data);
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
}
