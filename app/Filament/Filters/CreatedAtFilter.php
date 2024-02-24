<?php

namespace App\Filament\Filters;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CreatedAtFilter extends Filter
{
    protected function setUp(): void
    {
        $this->label('Created At')
            ->form([
                DatePicker::make('created_from'),
                DatePicker::make('created_until'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                    ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];
                if ($data['created_from'] ?? null) {
                    $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                }
                if ($data['created_until'] ?? null) {
                    $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                }

                return $indicators;
            });
    }
}
