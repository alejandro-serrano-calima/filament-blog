<?php

namespace Stephenjude\FilamentBlog\Resources\CommentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Stephenjude\FilamentBlog\Resources\CommentResource;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;
}
