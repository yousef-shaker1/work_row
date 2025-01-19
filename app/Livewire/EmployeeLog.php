<?php

namespace App\Livewire;

use App\Models\Check;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class EmployeeLog extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public Model $record;

    public function table(Table $table): Table
    {
        return $table
            ->heading($this->record->full_name)
            ->description('All Employee Attendaces & Breaks')
            ->query(Check::where('employee_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('day'),
                Tables\Columns\TextColumn::make('check_in'),
                Tables\Columns\TextColumn::make('check_out'),
                Tables\Columns\TextColumn::make('break_time'),
            ]);
    }

    public function render()
    {
        return view('livewire.employee-log');
    }
}
