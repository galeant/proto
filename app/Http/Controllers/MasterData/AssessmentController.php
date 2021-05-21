<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;


class AssessmentController extends Controller
{

    public $rule = [
        'aspek' => 'required|max:255',
        'parameter' => 'array',
        'parameter.*' => 'required',
    ];

    public function index(Request $request)
    {
        try {
            if ($request->has('json')) {
                $data = new Assessment;
                if ($request->has('aspek')) {
                    $data = $data->where('code_wilayah', 'ilike', '%' . $request->kode_wilayah . '%');
                }

                $length = $data->count();
                $data = $data->offset($request->start)->limit($request->length)->orderBy('id', 'asc')->get();

                $res["draw"] = (int) request('draw', 1);
                $res["recordsTotal"] = $length;
                $res["recordsFiltered"] = $length;
                $res["data"] = $data->transform(function ($v) {
                    $v->update_url = route('master_data.ass.update', ['id' => $v->id]);
                    $v->delete_url = route('master_data.branch.delete', ['id' => $v->id]);
                    return $v;
                });
                return response()->json($res, 200);
            }
            return view('master_data.branch');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $validator = validation($request, $this->rule);
        if ($validator['status'] == 'fail') {
            return response()->json($validator['message'], 500);
        }
        DB::beginTransaction();
        try {
            Branch::create([
                'code_wilayah' => $request->kode_wilayah,
                'code_cabang' => $request->kode_cabang,
                'code_outlet' => $request->kode_outlet,
                'abr_cabang' => $request->abr_cabang,
                'name_outlet' => $request->nama_outlet,
                'status' => $request->status,
            ]);
            DB::commit();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = validation($request, $this->rule);
        if ($validator['status'] == 'fail') {
            return response()->json($validator['message'], 500);
        }
        DB::beginTransaction();
        try {
            $data = Branch::where('id', $id)->firstOrFail();
            $data->update([
                'code_wilayah' => $request->kode_wilayah,
                'code_cabang' => $request->kode_cabang,
                'code_outlet' => $request->kode_outlet,
                'abr_cabang' => $request->abr_cabang,
                'name_outlet' => $request->nama_outlet,
                'status' => $request->status,
            ]);
            DB::commit();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = Branch::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json('success', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}
