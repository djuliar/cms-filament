<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Set;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Collection;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class ArticleResource extends Resource
{
	protected static ?string $model = Article::class;

	protected static ?string $navigationGroup = 'News';
	protected static ?string $navigationIcon = 'heroicon-o-newspaper';
	protected static ?string $navigationLabel = 'Articles';
	protected static ?string $label = 'Article';
	protected static ?int $navigationSort = 1;

	public static function form(Form $form): Form
	{
		return $form->schema([
			Tabs::make('Article')->columnSpanFull()->tabs([
				Tab::make('Article Section')->icon('heroicon-m-document-text')->schema([
					Forms\Components\Hidden::make('user_id')
						->default(fn() => Auth::user()->id)
						->hidden(fn(string $operation): bool => $operation === 'edit'),
					Forms\Components\Hidden::make('user_id_last_edit')
						->formatStateUsing(fn($state) => Auth::user()->id),

					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\TextInput::make('title')
							->maxLength(50)
							->live(onBlur: true)
							->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
							->columnSpan(8)
							->required(),
						Forms\Components\TextInput::make('slug')
							->helperText('Auto generate from title, if left empty.')
							->maxLength(50)
							->unique(ignoreRecord: true)
							->columnSpan(4),
					]),

					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\Select::make('category_id')
							->relationship('category', 'name')
							->searchable()
							->columnSpan(2)
							->required(),
						Forms\Components\Select::make('article_tag')
							->relationship('tags', 'name')
							->multiple()
							->searchable()
							->columnSpan(4)
							->createOptionForm([
								Forms\Components\TextInput::make('name')
									->maxLength(50)
									->live(onBlur: true)
									->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
									->columnSpan(1)
									->required()
									->unique(ignoreRecord: true),
								Forms\Components\Hidden::make('slug')
									->unique(ignoreRecord: true),
							]),
						Forms\Components\DatePicker::make('date')
							->columnSpan(2)
							->required(),
						Forms\Components\ToggleButtons::make('featured')
							->boolean()
							->inline()
							->default(false)
							->columnSpan(2)
							->required(),
						Forms\Components\ToggleButtons::make('editable')
							->boolean()
							->inline()
							->default(true)
							->columnSpan(2)
							->required(),
					]),

					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\RichEditor::make('content')
							->required()
							->columnSpan(8),
						Forms\Components\FileUpload::make('image')
							->image()
							->directory('articles')
							->openable()
							->downloadable()
							->columnSpan(4),
					]),

					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\Select::make('status')
							->options([
								'0' => 'Draft',
								'1' => 'Publish',
							])
							->default('0')
							->columnSpan(4)
							->required(),
					]),
				]),

				Tab::make('Meta Information')->icon('heroicon-m-information-circle')->schema([
					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\TextInput::make('meta_description')
							->label('Meta Description')
							->columnSpan(4),
						Forms\Components\TextInput::make('meta_author')
							->label('Meta Author')
							->columnSpan(4),
						Forms\Components\TagsInput::make('meta_keyword')
							->placeholder('Add keywords')
							->label('Meta Keywords')
							->color('primary')
							->splitKeys(['Tab', ' '])
							->reorderable()
							->columnSpan(4),
					]),
					Forms\Components\Group::make()->columns(12)->schema([
						Forms\Components\TextInput::make('og_title')
							->label('OG Title')
							->columnSpan(5),
						Forms\Components\TextInput::make('og_description')
							->label('OG Description')
							->columnSpan(5),
						Forms\Components\FileUpload::make('og_image')
							->label('OG Image')
							->image()
							->directory('articles/og_images')
							->columnSpan(2),
					]),
				]),
			]),
		]);
	}

	public static function table(Table $table): Table
	{
		return $table->columns([
			Tables\Columns\TextColumn::make('title')
				->words(10)
                ->wrap()
				->searchable(isIndividual: false),
			Tables\Columns\TextColumn::make('date')
				->date()
				->sortable(),
			Tables\Columns\TextColumn::make('user.name')
				->words(2)
				->searchable(),
			Tables\Columns\ImageColumn::make('image')
				->size(50),
			Tables\Columns\TextColumn::make('category.name')
				->badge()
				->sortable(),
            // Multiple tags column preview
            Tables\Columns\TextColumn::make('tags')->badge()->state(function (Article $record): array {
                return $record->tags->pluck('name')->toArray();
            })->color('gray'),
            // End of multiple tags column preview
			Tables\Columns\IconColumn::make('featured')
				->boolean(),
			Tables\Columns\TextColumn::make('status')
				->badge(),

			Tables\Columns\TextColumn::make('created_at')
				->dateTime()
				->sortable()
				->toggleable(isToggledHiddenByDefault: true),
			Tables\Columns\TextColumn::make('updated_at')
				->dateTime()
				->sortable()
				->toggleable(isToggledHiddenByDefault: true),
			Tables\Columns\TextColumn::make('deleted_at')
				->dateTime()
				->sortable()
				->toggleable(isToggledHiddenByDefault: true),
		])
			->filters([
                Tables\Filters\TrashedFilter::make(),
				Tables\Filters\SelectFilter::make('category_id')
					->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('tags')
					->relationship('tags', 'name'),
			])
			->actions([
				Tables\Actions\ActionGroup::make([
					Tables\Actions\Action::make('Show Articles')
						->icon('heroicon-o-newspaper')
						->url(fn(Article $record): string => route('page', ['id' => $record->id]))
						->openUrlInNewTab()
						->color('success'),
					Tables\Actions\ViewAction::make(),
					Tables\Actions\EditAction::make(),
					Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
				]),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Change Status')
						->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(['1' => 'Publish', '0' => 'Draft'])
                                ->selectablePlaceholder(false)
                                ->default('1')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function($record) use ($data){
                                Article::where('id', $record->id)->withTrashed()->update(['status' => $data['status']]);
                            });
                        }),
					Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
			'index' => Pages\ListArticles::route('/'),
			'view' => Pages\ViewArticle::route('/{record}'),
			'create' => Pages\CreateArticle::route('/create'),
			'edit' => Pages\EditArticle::route('/{record}/edit'),
		];
	}

	public static function getNavigationBadge(): ?string
	{
		return static::getModel()::count();
	}

	public static function getNavigationBadgeTooltip(): ?string
	{
		return 'The number of articles';
	}

	public static function getNavigationBadgeColor(): ?string
	{
		return static::getModel()::count() > 5 ? 'success' : 'danger';
	}

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Article Information')
                    ->columnSpanFull()
                    ->icon('heroicon-o-newspaper')
                    ->description('Detailed information about the article')
                    ->schema([
                        Infolists\Components\Group::make()
                            ->columns(12)
                            ->schema([
                                Infolists\Components\TextEntry::make('title')->columnSpan(4),
                                Infolists\Components\TextEntry::make('slug')->columnSpan(2),
                                Infolists\Components\TextEntry::make('date')->datetime()->columnSpan(2),
                                Infolists\Components\TextEntry::make('user.name')->columnSpan(2),
                                Infolists\Components\TextEntry::make('last_edit.name')->label('Last Edited By')->columnSpan(2),

                                Infolists\Components\TextEntry::make('category.name')->badge()->columnSpan(2),
                                Infolists\Components\TextEntry::make('tags')->badge()->state(function (Article $record): array {
                                    return $record->tags->pluck('name')->toArray();
                                })->color('gray')->columnSpan(4),
                                Infolists\Components\IconEntry::make('featured')->boolean()->columnSpan(2),
                                Infolists\Components\IconEntry::make('editable')->boolean()->columnSpan(2),
                                Infolists\Components\TextEntry::make('read_count')->columnSpan(2),

                                Infolists\Components\TextEntry::make('content')->html()->columnSpan(12),
                                Infolists\Components\ImageEntry::make('image')->columnSpan(2),
                                Infolists\Components\TextEntry::make('status')->badge()->columnSpan(2),
                                Infolists\Components\TextEntry::make('created_at')->dateTime()->columnSpan(2),
                                Infolists\Components\TextEntry::make('updated_at')->dateTime()->columnSpan(2),
                                Infolists\Components\TextEntry::make('deleted_at')->dateTime()->columnSpan(2),
                            ]),
                    ]),
            ]);
    }
}
