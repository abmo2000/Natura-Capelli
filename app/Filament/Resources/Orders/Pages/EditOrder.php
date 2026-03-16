<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            //DeleteAction::make(),
        ];
    }

    /**
     * Mutate form data before filling the form.
     * Pull customer relationship fields into flat form fields.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $customer = $this->record->customer;

        $data['customer_name'] = $customer?->name ?? '';
        $data['customer_email'] = $customer?->email ?? '';
        $data['customer_phone'] = $customer?->phone ?? '';
        $data['customer_insta_account'] = $customer?->insta_account ?? '';

        return $data;
    }

    /**
     * After saving the order, update the related customer record.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract customer fields — they don't exist on the orders table
        $customerData = [
            'name' => $data['customer_name'] ?? null,
            'email' => $data['customer_email'] ?? null,
            'phone' => $data['customer_phone'] ?? null,
        ];

        if (isset($data['customer_insta_account'])) {
            $customerData['insta_account'] = $data['customer_insta_account'];
        }

        // Update the related customer model
        $this->record->customer?->update($customerData);

        // Remove virtual fields so Eloquent doesn't try to save them on the orders table
        unset($data['customer_name'], $data['customer_email'], $data['customer_phone'], $data['customer_insta_account']);

        return $data;
    }
}
