<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
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

class TagResource extends Resource
{
	protected static ?string $model = Tag::class;

	protected static ?string $navigationGroup = 'News';
	protected static ?string $navigationIcon = 'heroicon-o-tag';
	protected static ?string $navigationParentItem = 'Articles';
	protected static ?int $navigationSort = 3;
	protected static ?string $navigationLabel = 'Tags';
	protected static ?string $label = 'Tag';
	// protected static ?string $pluralLabel = 'Tags';
	// protected static ?string $slug = 'tags';

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
									->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
									->helperText('This will be used as the tag name.')
									->columnSpan(6)
									->required(),
								Forms\Components\TextInput::make('slug')
									->hint('Auto generate from name, if left empty.')
									->unique(ignoreRecord: true)
									->maxLength(50)
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
			->defaultSort('created_at', 'desc')
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
				Tables\Actions\ActionGroup::make([
					Tables\Actions\EditAction::make(),
					Tables\Actions\DeleteAction::make(),
				]),
			], position: ActionsPosition::AfterColumns)
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
			'index' => Pages\ListTags::route('/'),
			'create' => Pages\CreateTag::route('/create'),
			'edit' => Pages\EditTag::route('/{record}/edit'),
		];
	}

	public static function getNavigationBadge(): ?string
	{
		return static::getModel()::count();
	}

	public static function getNavigationBadgeTooltip(): ?string
	{
		return 'The number of tags';
	}

	public static function getNavigationBadgeColor(): ?string
	{
		return static::getModel()::count() > 5 ? 'success' : 'danger';
	}
}
