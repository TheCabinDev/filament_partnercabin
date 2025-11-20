<?php

namespace Database\Seeders;

use App\Models\ClaimCodeRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ClaimCodRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/seeders/data/claimcoderecordseeder.json");
        $decoded = json_decode($json);

        foreach ($decoded as $key => $value) {
            ClaimCodeRecord::create([
                "id" => Str::uuid(),
                "id_partner" => $value->id_partner,
                "id_code" => $value->id_code,
                "reservation_id" => $value->reservation_id,
                "reservation_total_price" => $value->reservation_total_price,
                "total_poin_earned" => $value->total_poin_earned,
                "reservation_status" => $value->reservation_status,
            ]);
        }
    }
}
