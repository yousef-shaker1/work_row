<?php

namespace App\Filament\Employee\Resources;

use Carbon\Carbon;
use App\Models\Log;
use Filament\Forms;
use Filament\Tables;
use App\Models\Check;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Employee\Resources\CheckResource\Pages;
use App\Filament\Employee\Resources\CheckResource\RelationManagers;

class CheckResource extends Resource
{
    protected static ?string $model = Check::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Attendance & Shifts';


    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('employee_id', Auth::guard('employee')->user()->id);
    }


    public static function form(Form $form): Form
    {
        date_default_timezone_set('Africa/Cairo');
        return $form
            ->schema([
                TextInput::make('employee_id')->default(Auth::guard('employee')->user()->id)->disabled(),
                TextInput::make('name')->default(Auth::guard('employee')->user()->first_name)->disabled(),
                DatePicker::make('day')->default(now()),
                TimePicker::make('check_in')->default(now()->addHours(2)),
                TimePicker::make('check_out'),
            ]);
    }

    //create علشان اشيل زرار ال 
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),

                Tables\Columns\TextColumn::make('day')
                    ->label('Day'),

                Tables\Columns\TextColumn::make('check_in')
                    ->label('checkin')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    }),

                Tables\Columns\TextColumn::make('check_out')
                    ->label('Check Out')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    }),

                Tables\Columns\TextColumn::make('break_time')
                    ->label('Break (Mins)')
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 2);
                    }),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // End Break
                Tables\Actions\Action::make('EndBreak')
                    ->label('End Break')
                    ->action(function () {
                        if (!self::todayCheck()) {
                            self::sendNotifications('Error!', 'No Breaks Allowed Out Of The Shift!');
                            return;
                        }
                        if (!session()->get('stopwatch')) {
                            self::sendNotifications('Warning!', 'You Must Start Break Timer First!');
                            return;
                        }

                        $start = session()->get('stopwatch');
                        $start = Carbon::createFromFormat('H:i:s', $start);  // تأكد من تنسيق الوقت بشكل صحيح
                        $endTime = Carbon::now(); // الحصول على الوقت الحالي
                        $elapsed = $start->diffInMinutes($endTime); // حساب الفرق في الدقائق
                        $elapsed = number_format($elapsed, 2);

                        self::sendNotifications('Success!', "Your Break Has Just Ended With {$elapsed} Mins!");
                        self::logMessage(now() . " => " . Auth::guard('employee')->user()->first_name . " took a break for " . $elapsed . " Mins");

                        // تحديث الجلسة
                        session()->forget('stopwatch');
                        $myBreaks = session()->get('breaks', 0); // إذا لم تكن القيمة موجودة في الجلسة، اجعلها صفر
                        $myBreaks += $elapsed;
                        session()->put('breaks', $myBreaks);
                        return;
                    })
                    ->color('warning')
                    ->visible(function () {
                        return session()->get('stopwatch') !== null;
                    }),

                // Start Break
                Tables\Actions\Action::make('StartBreak')
                    ->label('Start Break')
                    ->action(function () {
                        if (!session()->has('breaks')) {//has result true or false
                            session()->put('breaks', 0);//put value to session
                        }
                        if (!self::todayCheck()) {
                            self::sendNotifications('Error!', 'No Breaks Allowed Out Of The Shift!');
                            return;
                        }
                        if (!session()->get('stopwatch')) {
                            $currentTime = Carbon::now();
                            session()->put('stopwatch', $currentTime->format('H:i:s'));  // تخزين الوقت في الجلسة
                            self::sendNotifications('Success!', 'Your Break Has Just Started!');
                            return;
                        }
                        self::sendNotifications('Error!', 'Your Break Has Already Started');
                    })
                    ->color('warning')
                    ->visible(function () {
                        return session()->get('check_out') === false && session()->get('stopwatch') === null;
                    }),

                // Check-out
                Tables\Actions\Action::make('CheckOut')
                    ->label('Check-out')
                    ->action(function () {
                        $todayCheck = self::todayCheck();
                        if (!$todayCheck) {
                            self::sendNotifications('Warning!', 'You Must Check-in First!');
                            return;
                        }
                        session()->put('check_out', true);
                        if (!session()->get('stopwatch')) {
                            $todayCheck->update([
                                'check_out' => now()->addHours(2),
                                'break_time' => session()->get('breaks') > 0 ? session()->get('breaks') : 0
                            ]);
                            session()->forget('breaks');
                            self::logMessage(now() . " => " . Auth::guard('employee')->user()->first_name . " finished working.");
                            return;
                        }
                        self::sendNotifications('Warning!', 'You Must Stop Break First!');
                    })
                    ->color('danger')
                    ->visible(function () {
                        return self::todayCheck() !== null && !session()->get('check_out') && session()->get('stopwatch') === null;
                    }),

                // Check-in
                Tables\Actions\Action::make('CheckIn')
                    ->label('Check-in')
                    ->action(function () {
                        if (self::todayCheck()) {
                            self::sendNotifications('Error!', 'You Must Check-out First!');
                            return;
                        } else {
                            Check::create([
                                'name' => Auth::guard('employee')->user()->first_name,
                                'employee_id' => Auth::guard('employee')->user()->id,
                                'day' => now(),
                                'check_in' => now()->addHours(2),
                            ]);
                            session()->put('check_out', false);
                            $logMessage = now() . " => " . Auth::guard('employee')->user()->first_name . " started working.";
                            self::logMessage($logMessage);
                            return;
                        }
                    })
                    ->color('primary')
                    ->visible(function () {
                        return self::todayCheck() === null;
                    }),

            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    //بيتاكد ان الموظف سجل الدخول النهاردة و ليا مسجلش الخروج
    public static function todayCheck()
    {
        return Check::where('employee_id', Auth::guard('employee')->user()->id)->where('check_out', null)->first();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecks::route('/'),
            'create' => Pages\CreateCheck::route('/create'),
            // 'edit' => Pages\EditCheck::route('/{record}/edit'),
        ];
    }

    public static function sendNotifications($title, $body)
    {
        return Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }

    public static function logMessage($message)
    {
        Log::create([
            'message' => $message,
            'employee_id' => Auth::guard('employee')->user()->id
        ]);
    }
}
