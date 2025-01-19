<?php

namespace App\Filament\Resources\Employee;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\GenderEnum;

use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Enums\EmploymentTypeEnum;
use App\Models\Employee\Employee;


use App\Enums\EmploymentStatusEnum;
use Filament\Forms\Components\Tabs;
use App\Enums\ResignationStatusEnum;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Employee\EmployeeResource\Pages;
use App\Filament\Resources\Employee\EmployeeResource\RelationManagers;
use App\Filament\Admin\Resources\User\EmployeeResource\Pages\ViewEmployeeLog;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Auth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Employee Details') // اسم المجموعة
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\TextInput::make('company_id')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('middle_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('date_of_birth'),
                                Forms\Components\ToggleButtons::make('gender')
                                    ->options(GenderEnum::class)
                                    ->inline()
                                    ->columnSpanFull(),

                                // Toggle::make('is_admin')
                                // ->inline()
                                // ->required(),
                            ])
                            ->columns(3),
                            Tabs\Tab::make('Employment Info')
                            ->schema([
                                Forms\Components\TextInput::make('job_title')
                                    ->required(),
                                Forms\Components\TextInput::make('salary')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->inputMode('decimal')
                                    ->minValue(0),
                                Forms\Components\DatePicker::make('hire_date')
                                    ->required(),
                                Forms\Components\Select::make('branch_id')
                                    ->relationship(
                                        name: 'branch',
                                        titleAttribute: 'name',
                                        // modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                    )
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('department_id')
                                    ->relationship(
                                        name: 'department',
                                        titleAttribute: 'name',
                                        // modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                    )
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\ToggleButtons::make('status')
                                    ->options(EmploymentStatusEnum::class)
                                    ->default(EmploymentStatusEnum::ACTIVE)
                                    ->inline()
                                    ->columnSpanFull(),
                                Forms\Components\ToggleButtons::make('employement_type')
                                    ->options(EmploymentTypeEnum::class)
                                    ->inline()
                                    ->columnSpanFull(),

                            ])
                            ->columns(2),

                            Tabs\Tab::make('Residency')
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Fieldset::make('Civil ID')
                                        ->schema([
                                            Forms\Components\TextInput::make('civil_id_number')
                                                ->label('Civil ID number')
                                                ->maxLength(255),
                                            Forms\Components\FileUpload::make('civil_id_expiration')
                                                ->label('Civil ID expiration')
                                            // SpatieMediaLibraryFileUpload::make('civil_id_image')
                                                // ->collection('civil-ids')
                                                ->image()
                                                ->imageEditor()
                                                ->downloadable()
                                                ->openable()
                                                ->columnSpanFull(),
                                        ]),
                                    Forms\Components\Fieldset::make('Passport')
                                        ->schema([
                                            Forms\Components\TextInput::make('passport_number')
                                                ->maxLength(255),
                                            Forms\Components\FileUpload::make('passport_expiration')
                                            // Forms\Components\SpatieMediaLibraryFileUpload::make('passport_image')
                                                // ->collection('passports')
                                                ->image()
                                                ->imageEditor()
                                                ->downloadable()
                                                ->openable()
                                                ->columnSpanFull(),
                                        ]),
                                    Forms\Components\Fieldset::make('Iqama')
                                        ->schema([
                                            Forms\Components\TextInput::make('iqama_number')
                                                ->maxLength(255),
                                            Forms\Components\FileUpload::make('iqama_expiration')
                                            // Forms\Components\SpatieMediaLibraryFileUpload::make('iqama_image')
                                                // ->collection('iqamas')
                                                ->image()
                                                ->imageEditor()
                                                ->downloadable()
                                                ->openable()
                                                ->columnSpanFull(),
                                        ]),
                                ])
                                    ->relationship(
                                        'residency',
                                        function (array $state) {
                                            $condition = filled($state['civil_id_number']) || filled($state['civil_id_expiration']) || filled($state['passport_number']) || filled($state['passport_expiration']) || filled($state['iqama_number']) || filled($state['iqama_expiration']) || filled($state['civil_id_image']) || filled($state['passport_image']) || filled($state['iqama_image']);
                                            return $condition;
                                        }
                                    )
                                    ->columns(3),
                            ]),
                            Tabs\Tab::make('Contact & Address')
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('work_email')
                                        ->email()
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\TextInput::make('phone')
                                        ->unique(ignoreRecord: true),
                                ])
                                    ->columns(2),

                                    Forms\Components\Fieldset::make('address')
                                    ->schema([
                                        Forms\Components\Select::make('country_id')
                                        ->relationship(name: 'country', titleAttribute: 'name')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Set $set) {
                                            $set('governorate_id', null);
                                        }),
                
                                        Forms\Components\Select::make('governorate_id')
                                        ->relationship(
                                            name: 'governorate',
                                            titleAttribute: 'name',
                                            modifyQueryUsing: fn(Builder $query, Forms\Get $get) => $query->where('country_id', $get('country_id')),
                                            )
                                        ->preload()
                                        ->searchable(),
                                        Forms\Components\Textarea::make('address')
                                        ->label('Address')
                                        ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Login Info') // التبويب الثالث
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->maxLength(255),
                            ]),

                            Tabs\Tab::make('Education')
                            ->schema([
                                Forms\Components\Repeater::make('education')
                                    ->hiddenLabel()
                                    ->relationship('educations')
                                    ->schema([
                                        Forms\Components\TextInput::make('institution')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('degree')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('field_of_study')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('graduation_year')
                                            ->numeric()
                                            ->minValue(2005)
                                            ->maxValue(2030)
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->reorderable()
                            ]),

                            Tabs\Tab::make('Experience')
                            ->schema([
                                Forms\Components\Repeater::make('experience')
                                    ->hiddenLabel()
                                    ->relationship('experiences')
                                    ->schema([
                                        Forms\Components\TextInput::make('company')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('job_title')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('start_date')
                                            ->required(),
                                        Forms\Components\DatePicker::make('end_date')
                                            ->required(),
                                        Forms\Components\MarkdownEditor::make('description')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->reorderable()

                            ]),

                        Tabs\Tab::make('Work History')
                            ->schema([
                                Forms\Components\Repeater::make('work_history')
                                    ->hiddenLabel()
                                    ->relationship('workHistories')
                                    ->schema([
                                        Forms\Components\TextInput::make('position')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('branch_id')
                                            ->relationship(
                                                name: 'branch',
                                                titleAttribute: 'name',
                                                // modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                            )
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('department_id')
                                            ->relationship(
                                                name: 'department',
                                                titleAttribute: 'name',
                                                // modifyQueryUsing: fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
                                            )
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\DatePicker::make('start_date')
                                            ->required(),
                                        Forms\Components\DatePicker::make('end_date')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->reorderable()
                            ]),

                        // Tabs\Tab::make('Resignation')
                        //     ->schema([
                        //         Forms\Components\Repeater::make('resignation')
                        //             ->hiddenLabel()
                        //             ->relationship('resignation')
                        //             ->schema([
                        //                 Forms\Components\DatePicker::make('resignation_date')
                        //                     ->required(),
                        //                 Forms\Components\DatePicker::make('last_working_day'),
                        //                 Forms\Components\ToggleButtons::make('status')
                        //                     ->options(ResignationStatusEnum::class)
                        //                     ->inline(),
                        //                 Forms\Components\MarkdownEditor::make('reason')
                        //                     ->columnSpanFull(),
                        //                 Forms\Components\MarkdownEditor::make('notes')
                        //                     ->columnSpanFull(),
                        //             ])
                        //             ->columns(2)
                        //             ->defaultItems(0)
                        //             ->collapsible()
                        //             ->reorderable()
                        //     ]),
                    ])
                    ->columnSpanFull()

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('country')
                    // ->StateUsing(fn($record) => $record->country->name . ', ' . $record->governorate->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('governorate.name')
                    ->label('governorate')
                    // ->StateUsing(fn($record) => $record->country->name . ', ' . $record->governorate->name)
                    ->sortable(),
                    

                // Tables\Columns\TextColumn::make('location')
                //     ->sortable()
                //     ->label('Location')
                //     ->GetStateUsing(function ($record) {
                //         return $record->country->name . ',' . $record->governorate->name;
                //     }),

                Tables\Columns\TextColumn::make('gender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
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
                // Tables\Actions\EditAction::make(),
                ])
                ->actions([
                    Tables\Actions\Action::make('View Log')
                        ->icon('heroicon-m-clipboard-document-list')
                        ->color('secondary')
                        ->url(fn(Employee $record): string => static::getUrl('view-log', parameters: [
                            'record' => $record,
                        ])),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
            'view-log' => ViewEmployeeLog::route('/{record}/log'),

        ];
    }
}
