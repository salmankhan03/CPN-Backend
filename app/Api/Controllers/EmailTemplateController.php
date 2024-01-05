<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\TempTemplateImages;
use App\Models\TempTemplateStore;
use Illuminate\Http\Request;


class EmailTemplateController extends Controller
{

    public function upsert(Request $request)
    {
        try {
            $data = $request->only(
                'id',
                'name',
                'body',
                'from_email',
                'to_email',
            );

            EmailTemplate::updateOrCreate(['id' => $data['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Email Template Saved Successfully',
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
            $obj = EmailTemplate::find($id);

            if ($obj) {

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Email Template Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Email Template Not Found'
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

            $list = EmailTemplate::paginate($request->get('pageSize'));

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
            $template = EmailTemplate::find($id);

            return response()->json([
                'status_code' => 200,
                'data' => $template
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

            EmailTemplate::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Email Templates Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
