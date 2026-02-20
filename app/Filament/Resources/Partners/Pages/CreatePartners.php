<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Partners\PartnersResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartners extends CreateRecord
{
    protected static string $resource = PartnersResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        //set email to lowercase to prevent case sensitivity issues
        $data['email'] = strtolower($data['email']);
        
        dd($data);
        return $data;
    }

}
