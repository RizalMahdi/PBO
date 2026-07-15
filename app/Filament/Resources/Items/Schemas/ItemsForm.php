<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_item')
                    ->required(),
                TextInput::make('jumlah_item')
                    ->numeric(),
                TextInput::make('harga_item')
                    ->required()
                    ->numeric(),
                Radio::make('category_id')
                    ->options(fn () => Category::pluck('name', 'id'))
                    ->required(),
                FileUpload::make('images')
                    ->image()
                    ->disk('public')
                    ->directory('item-images')
                    ->maxSize(2048),
            ]);
    }
}
