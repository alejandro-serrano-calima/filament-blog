<?php

namespace Stephenjude\FilamentBlog\Resources\BlogResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Stephenjude\FilamentBlog\Resources\BlogResource;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
