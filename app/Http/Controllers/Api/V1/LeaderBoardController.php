<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClaimCodeRecord;
use App\Models\PartnersCode;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    /**
     * get leaderboard performa kode partner dengan filter tanggal check-in dan tanggal transaksi dibuat
     * GET /api/leaderboard?date_in=2024-01-01&date_out=2024-01-31
     */

    public function leaderboard(Request $request)
    {
        // 1. Validasi input rentang tanggal (opsional)
        $request->validate([
            'date_in'  => 'nullable|date|date_format:Y-m-d',
            'date_out' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_in',
        ]);

        // 2. Siapkan variabel tanggal
        $startDate = $request->date_in ? Carbon::parse($request->date_in)->startOfDay() : null;
        $endDate   = $request->date_out ? Carbon::parse($request->date_out)->endOfDay() : null;

        // 3. Query Leaderboard
        $realData = PartnersCode::query()
            ->select(['id', 'unique_code'])
            // Subquery untuk hitung jumlah transaksi
            ->selectSub(function ($query) use ($startDate, $endDate) {
                $query->from('claim_code_records')
                    ->selectRaw('count(*)')
                    ->whereColumn('id_code', 'partners_codes.id')
                    ->where('reservation_status', 'SUCCESS')
                    ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate));
            }, 'transaksi_count')
            // Subquery untuk hitung total nominal
            ->selectSub(function ($query) use ($startDate, $endDate) {
                $query->from('claim_code_records')
                    ->selectRaw('COALESCE(SUM(total_poin_earned), 0)')
                    ->whereColumn('id_code', 'partners_codes.id')
                    ->where('reservation_status', 'SUCCESS')
                    ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate));
            }, 'nominal_total')
            // Urutkan berdasarkan transaksi terbanyak
            ->orderByDesc('transaksi_count')
            ->get();

        // Data dummy statis, set $dummies = collect([]) jika tidak ingin menampilkan data dummy atau data real sudah mandiri
        $dummies = collect([
            ['id' => 'd1', 'unique_code' => 'FLASHSL', 'transaksi_count' => 2, 'nominal_total' => '9500.00'],
            ['id' => 'd2', 'unique_code' => 'DISKBIG', 'transaksi_count' => 2, 'nominal_total' => '8900.00'],
            ['id' => 'd3', 'unique_code' => 'HEYSINI', 'transaksi_count' => 2, 'nominal_total' => '7800.00'],
            ['id' => 'd4', 'unique_code' => 'TOP99',   'transaksi_count' => 1, 'nominal_total' => '5500.00'],
            ['id' => 'd5', 'unique_code' => 'HEMATBGT', 'transaksi_count' => 1, 'nominal_total' => '5000.00'],
            ['id' => 'd6', 'unique_code' => 'IMAMS',   'transaksi_count' => 1, 'nominal_total' => '4900.00'],
            ['id' => 'd7', 'unique_code' => 'WELLSF',   'transaksi_count' => 1, 'nominal_total' => '4800.00'],
            ['id' => 'd8', 'unique_code' => 'NEWDSS',     'transaksi_count' => 1, 'nominal_total' => '4700.00'],
            ['id' => 'd9', 'unique_code' => 'TRYIT',    'transaksi_count' => 1, 'nominal_total' => '2200.00'],
            ['id' => 'd10', 'unique_code' => 'BEBERR',    'transaksi_count' => 1, 'nominal_total' => '2200.00'],
        ]);
        $finalData = $realData->concat($dummies)
            ->sortByDesc('transaksi_count')
            ->values()
            ->take(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Data leaderboard (Real + Dummy) berhasil dimuat',
            'data' => $finalData
        ]);
    }
}
