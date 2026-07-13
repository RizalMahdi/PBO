<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->disabled(),
                TextInput::make('customer_email')
                    ->disabled(),
                TextInput::make('username_roblox')
                    ->disabled(),
                TextInput::make('total_amount')
                    ->disabled()
                    ->prefix('Rp'),
                TextInput::make('status')
                    ->disabled(),
                Placeholder::make('created_at')
                    ->label('Transaction Date')
                    ->content(fn ($record) => $record?->created_at?->format('d M Y H:i')),
                Section::make('Items')
                    ->schema([
                        Placeholder::make('items_list')
                            ->label('')
                            ->content(function ($record) {
                                if (! $record || $record->items->isEmpty()) {
                                    return 'No items';
                                }

                                $html = '<div style="margin-top: 8px;">';
                                foreach ($record->items as $item) {
                                    $subtotal = number_format($item->subtotal, 0, ',', '.');
                                    $html .= "<div style=\"display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #1f2937;\">
                                        <span>{$item->nama_item} × {$item->quantity}</span>
                                        <span>Rp {$subtotal}</span>
                                    </div>";
                                }
                                $html .= '</div>';

                                return $html;
                            })->html(),
                    ]),
            ]);
    }
}
