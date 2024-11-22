<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enum\OrderStatusEnum;
use App\Enum\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use function PHPUnit\Framework\matches;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


    protected function getRedirectUrl(): string
    {
        return self::$resource::getUrl();
    }

    protected function afterCreate(): void
    {
        $this->record->payments()->create([
            'amount' => $this->record->amount,
            'status' => $this->getPaymentStatus()
        ]);
    }

    private function getPaymentStatus(): PaymentStatus
    {
        return match ($this->record->{Order::COLUMN_STATUS}->value) {
            OrderStatusEnum::PAID->value => PaymentStatus::APPROVED,
            OrderStatusEnum::CANCELED->value => PaymentStatus::CANCELED,
            OrderStatusEnum::PENDING->value => PaymentStatus::PENDING,
        };
    }
}
