<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use Filament\Forms\Set;
use Filament\Tables\Enums\ActionsPosition;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'News';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $label = 'Category';
    // protected static ?string $pluralLabel = 'Categories';
    // protected static ?string $slug = 'categories';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(12)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->maxLength(50)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->columnSpan(6)
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->helperText('Auto generate from name, if left empty.')
                                    ->maxLength(50)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(4),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        '1' => 'Active',
                                        '0' => 'Inactive',
                                    ])
                                    ->default('1')
                                    ->columnSpan(2)
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->paginatedWhileReordering()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ], position: ActionsPosition::AfterColumns)
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of categories';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 5 ? 'success' : 'danger';
    }
}
