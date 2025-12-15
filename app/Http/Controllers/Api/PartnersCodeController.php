<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnersCode;
use Illuminate\Support\Facades\Validator;

class PartnersCodeController extends Controller
{
    public function index()
    {
        $codes = PartnersCode::with(['partner', 'user'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $codes,
        ]);
    }

    public function show($id)
    {
        $code = PartnersCode::with(['partner', 'user'])->find($id);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode mitra tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $code,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_partner' => 'required|exists:partners,id',
            'id_creator' => 'required|exists:users,id',
            'unique_code' => [
                'required',
                'string',
                'unique:partners_codes,unique_code',
            ],
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'reduction_percentage' => 'nullable|numeric|min:0|max:50',
            'claim_quota' => 'nullable|integer|min:0',
            'max_claim_per_account' => 'nullable|integer|min:0',
            'use_started_at' => 'nullable|date',
            'use_expired_at' => 'nullable|date|after:use_started_at',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $code = PartnersCode::create($validator->validated());

        try {
            auth()->user()->notify(new PartnerNotification(
                'Kode Mitra Baru Dibuat',
                'Kode mitra "' . $code->unique_code . '" telah berhasil dibuat.',
                'info'
            ));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi kode mitra baru: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode mitra berhasil dibuat',
            'data' => $code,
        ]);
    }


    public function update(Request $request, $id)
    {
        $code = PartnersCode::find($id);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode mitra tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(),[
            'unique_code' => [
                'sometimes',
                'string',
                'unique:partners_codes,unique_code,' . $code->id,
            ],
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'reduction_percentage' => 'nullable|numeric|min:0|max:50',
            'claim_quota' => 'nullable|integer|min:0',
            'max_claim_per_account' => 'nullable|integer|min:0',
            'use_started_at' => 'nullable|date',
            'use_expired_at' => 'nullable|date|after:use_started_at',
            'status' => 'nullable|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $code->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kode mitra berhasil diperbarui',
            'data' => $code,
        ]);
    }


    public function destroy($id)
    {
        $code = PartnersCode::find($id);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode mitra tidak ditemukan',
            ], 404);
        }

        $code->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode mitra berhasil dihapus',
        ]);
    }
}
