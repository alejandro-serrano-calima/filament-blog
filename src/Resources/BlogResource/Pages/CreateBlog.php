<?php

namespace Stephenjude\FilamentBlog\Resources\BlogResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Stephenjude\FilamentBlog\Resources\BlogResource;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;
}
