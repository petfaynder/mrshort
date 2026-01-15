<?php

namespace App\Filament\Resources\CampaignTemplateResource\Pages;

use App\Filament\Resources\CampaignTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCampaignTemplate extends EditRecord
{
    protected static string $resource = CampaignTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sanitize data to prevent JSON parsing errors in Livewire
        $data = $this->sanitizeNestedData($data);
        return $data;
    }

    private function sanitizeNestedData(array $data): array
    {
        if (isset($data['campaignTemplateSteps'])) {
            foreach ($data['campaignTemplateSteps'] as &$step) {
                if (isset($step['campaignTemplateAds'])) {
                    foreach ($step['campaignTemplateAds'] as &$ad) {
                        if (isset($ad['ad_data']) && is_array($ad['ad_data'])) {
                            foreach ($ad['ad_data'] as $key => &$value) {
                                if (is_string($value) && !empty($value)) {
                                    // Ensure UTF-8 encoding
                                    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                                    // Remove null bytes that break JSON
                                    $value = str_replace("\0", '', $value);
                                    // Normalize line endings
                                    $value = str_replace(["\r\n", "\r"], "\n", $value);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
}
