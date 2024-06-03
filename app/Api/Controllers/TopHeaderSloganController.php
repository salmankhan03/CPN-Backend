<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TopHeaderSlogan;
use Illuminate\Http\Request;


class TopHeaderSloganController extends Controller
{

    public function upsert(Request $request)
    {
        try {
            $data = $request->only(
                'id',
                'text',
                'url'
            );

            TopHeaderSlogan::updateOrCreate(['id' => $data['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Top Header Slogan Created Successfully',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {

        try {
            $obj = TopHeaderSlogan::find($id);

            if ($obj) {

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Top Header Slogan Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Top Header Slogan Not Found'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        try {

            $list = TopHeaderSlogan::paginate($request->get('pageSize'));

            return response()->json([
                'status_code' => 200,
                'list' => $list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id)
    {

        try {
            $slogan = TopHeaderSlogan::find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $slogan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function multipleDelete(Request $request)
    {
        try {
            $ids = explode(",",  $request->only('ids')['ids']);

            TopHeaderSlogan::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Top Header Slogan Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

}
