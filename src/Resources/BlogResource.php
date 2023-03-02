<?php

namespace Stephenjude\FilamentBlog\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Stephenjude\FilamentBlog\Models\Blog;
use Stephenjude\FilamentBlog\Resources\BlogResource\Pages;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    use HasContentEditor;

    protected static ?string $model = Blog::class;

    protected static ?string $slug = 'blogs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blogs';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-blog::filament-blog.blog_name'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->label(__('filament-blog::filament-blog.slug'))
                    ->disabled()
                    ->required()
                    ->unique(Blog::class, 'slug', fn ($record) => $record),

                Forms\Components\Textarea::make('description')
                    ->label(__('filament-blog::filament-blog.description'))
                    ->rows(4)
                    ->minLength(5)
                    ->maxLength(1000)
                    ->columnSpan([
                        'sm' => 2,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-blog::filament-blog.blog_name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }    
}
