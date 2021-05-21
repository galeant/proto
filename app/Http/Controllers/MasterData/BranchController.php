<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;

use DB;

class BranchController extends Controller
{
    public $rule = [
        'kode_wilayah' => 'required|max:255',
        'kode_cabang' => 'required|max:255',
        'kode_outlet' => 'required|max:255',
        'abr_cabang' => 'required|max:255',
        'nama_outlet' => 'required|max:255',
        'status' => 'required|in:kc,kcp,kk',
    ];

    public function index(Request $request)
    {
        try {
            if ($request->has('json')) {
                $data = new Branch;
                if ($request->has('kode_wilayah')) {
                    $data = $data->where('code_wilayah', 'ilike', '%' . $request->kode_wilayah . '%');
                }

                if ($request->has('kode_cabang')) {
                    $data = $data->where('code_cabang', 'ilike', '%' . $request->kode_cabang . '%');
                }

                if ($request->has('kode_outlet')) {
                    $data = $data->where('code_outlet', 'ilike', '%' . $request->kode_outlet . '%');
                }

                if ($request->has('nama_outlet')) {
                    $data = $data->where('name_outlet', 'ilike', '%' . $request->nama_outlet . '%');
                }

                if ($request->has('status')) {
                    $data = $data->where('status', 'ilike', '%' . $request->status . '%');
                }

                $length = $data->count();
                $data = $data->offset($request->start)->limit($request->length)->orderBy('id', 'asc')->get();

                $res["draw"] = (int) request('draw', 1);
                $res["recordsTotal"] = $length;
                $res["recordsFiltered"] = $length;
                $res["data"] = $data->transform(function ($v) {
                    $v->update_url = route('master_data.branch.update', ['id' => $v->id]);
                    $v->delete_url = route('master_data.branch.delete', ['id' => $v->id]);
                    return $v;
                });
                return response()->json($res, 200);
            }
            return view('master_data.branch', [
                'create_url' => route('master_data.branch.create')
            ]);
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
