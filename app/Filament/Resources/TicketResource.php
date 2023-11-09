<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    private static function extractAirportCodes($str) {
        $pattern = '/\((.*?)\)/'; // This pattern matches anything inside parentheses
        preg_match_all($pattern, $str, $matches);
        if (count($matches[1]) == 2) { // Ensure there are two matches for airport codes
            return $matches[1][0] . ' - ' . $matches[1][1];
        }
        return '-'; // Return a default or error message if the format isn't as expected
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('confirmation_code')
                    ->label('PNR')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flightData.departure_city')
                    ->label('Departure')
                    ->getStateUsing(fn (Model $record) => self::extractAirportCodes($record->outboundFlight->departure_city . ' - ' . $record->outboundFlight->arrival_city))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('flightData.departure_date')
                    ->label('Return')
                    ->getStateUsing(fn (Model $record) => self::extractAirportCodes($record->returnFlight?->departure_city . ' - ' . $record->returnFlight?->arrival_city))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('passengers')
                    ->label('Passengers')
                    ->getStateUsing(fn (Model $record) => $record->passengers->count())
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('EUR')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agency.name')
                    ->label('Agency')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Payment date')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment method')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
