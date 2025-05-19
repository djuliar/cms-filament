<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Menu Items';
    protected static ?string $label = 'Menu Item';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Label')
                                    ->required()
                                    ->maxLength(100),
                                AdjacencyList::make('subject')
                                    ->maxDepth(2)
                                    ->form([
                                        Forms\Components\Group::make()
                                            ->columns(12)
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->columnSpan(12)
                                                    ->required(),
                                                Forms\Components\Select::make('type')
                                                    ->options([
                                                        'page' => 'Page',
                                                        'inlink' => 'Internal Link',
                                                        'exlink' => 'External Link',
                                                    ])
                                                    ->default('exlink')
                                                    ->columnSpan(3)
                                                    ->selectablePlaceholder(false),
                                                Forms\Components\TextInput::make('link')
                                                    ->columnSpan(6)
                                                    ->required(),
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        '1' => 'Active',
                                                        '0' => 'Inactive',
                                                    ])
                                                    ->default('1')
                                                    ->columnSpan(3)
                                                    ->required(),
                                            ]),
                                    ]),
                                // Forms\Components\Select::make('parent_id')
                                //     ->label('Parent Menu')
                                //     ->columnSpan(4),
                                // Forms\Components\Select::make('type')
                                //     ->options([
                                //         'page' => 'Page',
                                //         'inlink' => 'Internal Link',
                                //         'exlink' => 'External Link',
                                //     ])
                                //     ->default('exlink')
                                //     ->selectablePlaceholder(false)
                                //     ->columnSpan(2),
                                // Forms\Components\TextInput::make('link')
                                //     ->columnSpan(8)
                                //     ->maxLength(255),
                                // Forms\Components\Select::make('status')
                                //     ->options([
                                //         '1' => 'Active',
                                //         '0' => 'Inactive',
                                //     ])
                                //     ->default('1')
                                //     ->columnSpan(2)
                                //     ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('type')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('link')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('parent_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('page_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\IconColumn::make('status')
                //     ->boolean(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
			->emptyStateActions([
				Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
