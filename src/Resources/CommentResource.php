<?php

namespace Stephenjude\FilamentBlog\Resources;

use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function now;

use Stephenjude\FilamentBlog\Models\Comment;
use Stephenjude\FilamentBlog\Resources\CommentResource\Pages;

use Stephenjude\FilamentBlog\Traits\HasContentEditor;

class CommentResource extends Resource
{
    use HasContentEditor;

    protected static ?string $model = Comment::class;

    protected static ?string $slug = 'blogs/comments';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blogs';

    protected static ?string $navigationIcon = 'heroicon-o-chat';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('post_id')
                            ->label(__('filament-blog::filament-blog.post'))
                            ->relationship('post', 'title')
                            ->required(),

                        Forms\Components\Select::make('answer_to')
                            ->label(__('filament-blog::filament-blog.answer_to'))
                            ->relationship('parent', 'content')
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\Select::make('author_id')
                            ->label(__('filament-blog::filament-blog.author'))
                            ->relationship('author', 'email')
                            ->searchable()
                            ->required(),

                        Forms\Components\DatePicker::make('published_at')
                            ->label(__('filament-blog::filament-blog.published_date')),

                        Forms\Components\TextInput::make('content')
                            ->label(__('filament-blog::filament-blog.comment_content'))
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('filament-blog::filament-blog.created_at'))
                            ->content(fn (
                                ?Comment $record
                            ): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label(__('filament-blog::filament-blog.last_modified_at'))
                            ->content(fn (
                                ?Comment $record
                            ): string => $record ? $record->updated_at->diffForHumans() : '-'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('banner')
                    ->label(__('filament-blog::filament-blog.banner'))
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-blog::filament-blog.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('filament-blog::filament-blog.author_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('filament-blog::filament-blog.category_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('filament-blog::filament-blog.published_at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->placeholder(fn ($state): string => 'Dec 18, '.now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('published_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['author', 'category']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name', 'category.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->author) {
            $details['Author'] = $record->author->name;
        }

        if ($record->category) {
            $details['Category'] = $record->category->name;
        }

        return $details;
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-blog::filament-blog.comments');
    }

    public static function getModelLabel(): string
    {
        return __('filament-blog::filament-blog.comment');
    }
}
