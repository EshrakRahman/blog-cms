<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\PostStatus;


class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Posts'), // Added a label

            'published' => Tab::make('Published')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', PostStatus::PUBLISHED)),

            'draft' => Tab::make('Drafts')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', PostStatus::DRAFT)),
            'archived' => Tab::make('Archived')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', PostStatus::ARCHIVED)),
        ];
    }
}
