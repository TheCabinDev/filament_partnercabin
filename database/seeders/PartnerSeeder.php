<?php

namespace Database\Seeders;

use App\Models\Partners;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/seeders/data/partnerdataseeder.json");
        $decoded = json_decode($json);

        foreach ($decoded as $key => $value) {
            Partners::create([
                "creator_id" => $value->creator_id,
                "name" => $value->name,
                "email" => $value->email,
                "password" => bcrypt($value->password),
                "image_profile" => $value->image_profile,
                "status" => $value->status,
            ]);
        }
    }
}
