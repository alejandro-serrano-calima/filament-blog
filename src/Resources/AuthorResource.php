<?php

namespace Stephenjude\FilamentBlog\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Stephenjude\FilamentBlog\Models\Author;
use Stephenjude\FilamentBlog\Resources\AuthorResource\Pages;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;

class AuthorResource extends Resource
{
    use HasContentEditor;

    protected static ?string $model = Author::class;

    protected static ?string $slug = 'blogs/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blogs';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-blog::filament-blog.email'))
                            ->required()
                            ->email()
                            ->unique(Author::class, 'email', fn ($record) => $record),
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament-blog::filament-blog.password'))
                            ->password()
                            ->required(),
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('filament-blog::filament-blog.first_name'))
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label(__('filament-blog::filament-blog.last_name'))
                            ->required(),
                        Forms\Components\Select::make('blog_id')
                            ->multiple()
                            ->label(__('filament-blog::filament-blog.blogs'))
                            ->relationship('blogs', 'name')
                            ->required(),
                        Forms\Components\FileUpload::make('photo')
                            ->label(__('filament-blog::filament-blog.photo'))
                            ->image()
                            ->maxSize(5120)
                            ->directory('blog')
                            ->disk('public'),
                        self::getContentEditor('bio'),
                        Forms\Components\TextInput::make('github_handle')
                            ->label(__('filament-blog::filament-blog.github')),
                        Forms\Components\TextInput::make('twitter_handle')
                            ->label(__('filament-blog::filament-blog.twitter')),
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
                                ?Author $record
                            ): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label(__('filament-blog::filament-blog.last_modified_at'))
                            ->content(fn (
                                ?Author $record
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
                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('filament-blog::filament-blog.first_name'))
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('last_name')
                    ->label(__('filament-blog::filament-blog.last_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-blog::filament-blog.email'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAuthors::route('/'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-blog::filament-blog.authors');
    }

    public static function getModelLabel(): string
    {
        return __('filament-blog::filament-blog.author');
    }
}
