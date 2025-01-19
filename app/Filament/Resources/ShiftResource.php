<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Shift;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\ShiftResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ShiftResource\RelationManagers;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_id')
                ->required()
                ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->nullable()
                    ->label('Shift Name')
                    ->maxLength(255),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->required()
                    ->options([
                        'morning' => 'Morning',
                        'evening' => 'Evening',
                        'both' => 'Both',
                    ]),
                CheckboxList::make('work_days')
                    ->label('Work Days')
                    ->required()
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')->label('Start Time')->dateTime('H:i A'),
                Tables\Columns\TextColumn::make('end_time')->label('End Time')->dateTime('H:i A'),
                Tables\Columns\TextColumn::make('type'),
                TextColumn::make('work_days')
                ->label('Work Days')
                ->formatStateUsing(function ($state) {
                    if (is_string($state)) {
                        $stateArray = explode(', ', $state);
                    } else {
                        $stateArray = (array) $state;
                    }
                    return implode(', ', $stateArray);
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShift::route('/create'),
            'edit' => Pages\EditShift::route('/{record}/edit'),
        ];
    }
}
