<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEmployees extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'half';

    public static function canView(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()->where('date_hired', '>=', now()->subYear())
            )
            ->columns([
                TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
