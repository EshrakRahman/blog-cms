<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\PostStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use PHPUnit\Framework\Attributes\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title'),
                TextInput::make('slug')
                    ->label('slug'),
                RichEditor::make('content')
                    ->label('Blog content'),


                FileUpload::make('thumbnail')
                    ->disk('public')
                    ->directory('')
                    ->visibility('public'),
                Select::make('status')
                    ->options(PostStatus::class)

            ]);
    }
}
