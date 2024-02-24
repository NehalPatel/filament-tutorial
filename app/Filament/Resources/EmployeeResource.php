<?php

namespace App\Filament\Resources;

use App\Filament\Filters\CreatedAtFilter;
use App\Filament\Filters\DateFilter;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\City;
use App\Models\Employee;
use App\Models\State;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Employee Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Details')
                    ->description('Provide User details')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('middle_name')
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Section::make('User Address')
                    ->description('Provide Address Details')
                    ->schema(array(
                        Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->required()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->searchable(),
                        Select::make('state_id')
                            ->options(options: fn(Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->required()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                            ->live()
                            ->searchable(),
                        Select::make('city_id')
                            ->options(options: fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->searchable(),
                        Select::make('department_id')
                            ->relationship('department', titleAttribute: 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        TextInput::make('address')
                            ->maxLength(255),
                        TextInput::make('zipcode')
                            ->maxLength(255),
                    ))->columns(3),
                Forms\Components\Section::make('Dates')
                    ->schema([
                        DatePicker::make('date_of_birth')
                            ->displayFormat('d/M/Y')
                            ->native(false),
                        DatePicker::make('date_hired')
                            ->displayFormat('d/M/Y')
                            ->native(false),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                TextColumn::make('middle_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('zipcode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->filters([
                SelectFilter::make('Department')
                    ->label('Filter by Department')
                    ->indicator('Department')
                    ->relationship('department', 'name'),
                CreatedAtFilter::make('created_at'),
                DateFilter::make('date_of_birth')
                    ->label('Date of Birth')
                    ->column('date_of_birth')
            ])
            ->deferFilters()
            ->persistFiltersInSession()
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Employee Details')
                    ->schema([
                        TextEntry::make('first_name'),
                        TextEntry::make('last_name'),
                        TextEntry::make('middle_name'),
                    ])->columns(3),
                Section::make('Employee Address')
                    ->schema([
                        TextEntry::make('country.name'),
                        TextEntry::make('state.name'),
                        TextEntry::make('city.name'),
                        TextEntry::make('department.name'),
                        TextEntry::make('address'),
                        TextEntry::make('zipcode'),
                    ])->columns(2),
                Section::make('Dates')
                    ->schema([
                        TextEntry::make('date_hired'),
                        TextEntry::make('date_of_birth'),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            // 'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
