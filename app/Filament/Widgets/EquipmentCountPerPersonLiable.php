<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EquipmentCountPerPersonLiable extends BaseWidget
{
    protected int | string | array $columnSpan="full";
    public function table(Table $table): Table
    {
        
        return $table
            ->query(
                
            )
            ->columns([
                // ...
            ]);
    }
}
