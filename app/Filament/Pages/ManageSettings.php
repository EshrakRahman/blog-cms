<?php

namespace App\Filament\Pages;

use App\Models\BlogSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;
    protected string $view = 'filament.pages.manage-settings';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = BlogSetting::find(1);

        if ($settings) {
            // Just fill the array directly. 
            // Filament maps this to public ?array $data automatically.
            $this->form->fill($settings->toArray());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('General Configuration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Site Title')
                            ->required(),
                        Textarea::make('site_description')
                            ->label('Site Description')
                            ->columnSpanFull(),
                        FileUpload::make('logo')
                            ->image()
                            ->directory('site-assets')
                            ->disk('public'),
                        FileUpload::make('fav_icon')
                            ->label('Favicon')
                            ->image()
                            ->directory('site-assets')
                            ->disk('public'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Always target the record with ID 1
            BlogSetting::updateOrCreate(['id' => 1], $data);

            Notification::make()
                ->title('Settings updated successfully')
                ->success()
                ->send();
        } catch (\Exception $exception) {
            Notification::make()
                ->title('Error saving settings')
                ->danger()
                ->send();
        }
    }
}
