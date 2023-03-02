<?php

namespace Stephenjude\FilamentBlog\Resources\BlogResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Stephenjude\FilamentBlog\Resources\BlogResource;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
