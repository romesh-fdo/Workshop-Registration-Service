<?php

namespace App\Filament\Resources\Workshops;

use App\Filament\Resources\Workshops\Pages\CreateWorkshop;
use App\Filament\Resources\Workshops\Pages\EditWorkshop;
use App\Filament\Resources\Workshops\Pages\ListWorkshops;
use App\Filament\Resources\Workshops\Schemas\WorkshopForm;
use App\Filament\Resources\Workshops\Tables\WorkshopsTable;
use App\Models\Workshop;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Workshops';

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['Manager', 'Staff']) ?? false;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user !== null
            && $user->hasRole('Manager')
            && ! $user->hasRole('Admin');
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        return $user !== null
            && $user->hasRole('Manager')
            && ! $user->hasRole('Admin');
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return WorkshopForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkshopsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkshops::route('/'),
            'create' => CreateWorkshop::route('/create'),
            'edit' => EditWorkshop::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}