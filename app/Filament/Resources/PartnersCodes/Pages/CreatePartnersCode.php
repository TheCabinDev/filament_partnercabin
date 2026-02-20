<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnerCodeAssigned;
use Filament\Notifications\Notification;
use App\Traits\CodeCheck;

class CreatePartnersCode extends CreateRecord
{
    use CodeCheck;
    protected static string $resource = PartnersCodeResource::class;

    protected function beforeValidate(): void
    {

        $res = $this->data;
        $res['unique_code'] = strtoupper($res['unique_code']);    //make sure its capital
        $codeToCheckForUnique = $res['unique_code'];

        if(strlen($res['unique_code']) < 3 || strlen($res['unique_code']) > 15){
            $modalDescAction = "Create atau update KODE PARTNER gagal karena kode harus antara 3 hingga 15 karakter.";
            Notification::make()
                ->title($modalDescAction)
                ->warning()
                ->duration(5000)
                ->send();
            $this->halt();
        }

        $isCodeExist = $this->isCodeUnique($codeToCheckForUnique);
        
        if ($isCodeExist) {
            $modalDescAction = "Create atau update KODE PARTNER gagal karena duplikasi data ({$res['unique_code']}))";
            Notification::make()
                ->title($modalDescAction)
                ->warning()
                ->duration(5000)
                ->send();
            // 2. Hentikan proses agar tidak lanjut ke database
            $this->halt();
        }
        

      

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $res = $this->data;
          //generate qrcode dari services/QrCodeService.php dan save sebagai image di kolom qrcode_image
        $qrcodeService = new \App\Services\QrCodeService();
        
        $qrcodeImage = $qrcodeService->generateAndSave($res['unique_code']);
        $res['qrcode_image'] = $qrcodeImage; // Simpan path gambar QR code ke database

        $this->data = $res;

        return $this->data;
    }

    protected function afterCreate(): void
    {
        Mail::to($this->record->partner->email)->send(
            new PartnerCodeAssigned($this->record, $this->record->partner)
        );
    }
}
