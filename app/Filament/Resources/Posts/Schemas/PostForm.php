<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\PostStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->readOnly(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                RichEditor::make('content')
                    ->label('Blog content')
                    ->columnSpanFull()
                    ->extraInputAttributes([
                        'style' => 'height: 400px; overflow-y: auto;'
                    ])
                    ->required(),


                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('blog/thumbnail')
                    ->visibility('public'),
                Select::make('status')
                    ->options(PostStatus::class)

            ]);
    }
}
