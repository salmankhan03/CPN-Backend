<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TempTemplateImages;
use App\Models\TempTemplateStore;
use Illuminate\Http\Request;


class TempTemplateController extends Controller
{

    public function upsert(Request $request)
    {
        try {
            $data = $request->only(
                'id',
                'name',
                'template'
            );

            TempTemplateStore::updateOrCreate(['id' => $data['id']], $data);

            return response()->json([
                'status_code' => 200,
                'message'     => 'Template Saved Successfully',
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
            $obj = TempTemplateStore::find($id);

            if ($obj) {

                $obj->delete();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Template Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Template Not Found'
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

            $list = TempTemplateStore::with('images')->paginate($request->get('pageSize'));

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
            $template = TempTemplateStore::find($id);

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

            TempTemplateStore::whereIn('id', $ids)->delete();

            return response()->json([
                'status_code' => 200,
                'message' => 'Multiple Templates Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function imageUpload(Request $request)
    {
        try {


            $templateId = $request->get('template_id');

            if ($request->only('images')) {

                foreach ($request->only('images')['images'] as  $image) {

                    $imageData = [];

                    $imageData['original_name'] = $image->getClientOriginalName();
                    $imageData['product_id'] = $templateId;
                    $imageData['name'] = $image;

                    TempTemplateImages::create($imageData);
                }
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'Images Uploaded Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
