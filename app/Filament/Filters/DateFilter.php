<?php

namespace App\Filament\Filters;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DateFilter extends Filter
{
    // protected string $columnName = 'created_at';

    protected function setUp(): void
    {
        $this->label($this->label)
            ->form([
                DatePicker::make('date_from'),
                DatePicker::make('date_until'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when($data['date_from'], fn (Builder $query, $date): Builder => $query->whereDate($this->columnName, '>=', $date))
                    ->when($data['date_until'], fn (Builder $query, $date): Builder => $query->whereDate($this->columnName, '<=', $date));
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];
                if ($data['date_from'] ?? null) {
                    $indicators['date_from'] = Str::title(str_replace('_', ' ', $this->columnName)) . ' from ' . Carbon::parse($data['date_from'])->toFormattedDateString();
                }
                if ($data['date_until'] ?? null) {
                    $indicators['date_until'] = Str::title(str_replace('_', ' ', $this->columnName)) . ' until ' . Carbon::parse($data['date_until'])->toFormattedDateString();
                }

                return $indicators;
            });
    }

    public function column(string $columnName): self
    {
        $this->columnName = $columnName;
        return $this;
    }
}
