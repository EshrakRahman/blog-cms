<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Tag;
use App\PostStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
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
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Tag name')
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return Tag::create(
                            ['name' => $data['name']]
                        )->id;
                    }),


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
                    ->options(PostStatus::class),

                Section::make('SEO Metadata')
                    ->description('Optimize how this post appears in search engines.')
                    ->relationship('seo')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Title Tag')
                            ->placeholder('Target keyword - Brand Name')
                            ->maxLength(60),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->placeholder('A brief summary for Google search results...')
                            ->maxLength(160),

                        FileUpload::make('meta_image')
                            ->label('Social Share Image')
                            ->image()
                            ->directory('seo-images')
                            ->disk('public'),
                    ])
                    ->collapsible()
                    ->columnSpanFull()
                    ->collapsed(),

            ]);
    }
}
