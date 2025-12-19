<?php

namespace Database\Seeders;

use App\Models\PartnersCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PartnerCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/seeders/data/partnercodedataseeder.json");
        $decoded = json_decode($json);

        foreach ($decoded as $key => $value) {
            PartnersCode::create([
                "id_partner" => $value->id_partner,
                "id_creator" => $value->id_creator,
                "unique_code" => $value->unique_code,
                "fee_percentage" => $value->fee_percentage,
                "reduction_percentage" => $value->reduction_percentage,
                "claim_quota" => $value->claim_quota,
                "max_claim_per_account" => $value->max_claim_per_account,
                "use_started_at" => $value->use_started_at,
                "use_expired_at" => $value->use_expired_at,
                "status" => $value->status,
            ]);
        }
    }
}
