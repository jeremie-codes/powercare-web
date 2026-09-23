<?php

namespace App\Filament\Resources\Agents\Schemas;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Informations de l’agent')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('user_id')
                            ->label('Compte utilisateur')
                            ->options(User::where('role', 'agent')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Choisir un agent')
                            ->createOptionForm([
                                FileUpload::make('avatar')
                                    ->label('Photo de profil (optionnel)')
                                    ->image()
                                    ->directory('profiles')
                                    ->disk('public')
                                    ->maxSize(4096),
                                TextInput::make('name')->label('Nom complet')->required(),
                                TextInput::make('email')->email()->required(),
                                TextInput::make('phone')->tel()->unique()->maxLength(20),
                                TextInput::make('adresse')->label('Adresse'),
                                TextInput::make('password')
                                    ->label('Mot de passe')
                                    ->password()
                                    ->required()
                                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                                Toggle::make('is_active')->label('Compte actif')->default(true),
                            ])
                            ->createOptionUsing(fn (array $data) => User::create(array_merge($data, ['role' => 'agent']))->getKey())
                            ->createOptionAction(fn (Action $action) => $action
                                ->modalHeading('Créer un compte agent')
                                ->modalSubmitActionLabel('Créer')
                                ->modalWidth('lg')),

                        Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'nom')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Choisir un service')
                            ->createOptionForm([
                                FileUpload::make('image')
                                    ->disk('public')
                                    ->directory('services')
                                    ->required(),
                                TextInput::make('nom')->label('Nom du service')->required(),
                                TextInput::make('description')->required(),
                                TextInput::make('prix_base')->numeric()->required()->suffix('$ /mois'),
                                Toggle::make('is_actif')->label('Visible')->default(true),
                            ])
                            ->createOptionAction(fn (Action $action) => $action
                                ->modalHeading('Créer un service')
                                ->modalSubmitActionLabel('Créer')
                                ->modalWidth('lg')),

                        TextInput::make('experience')
                            ->label('Expérience (années)')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),

                        Select::make('disponibilite')
                            ->label('Disponibilité')
                            ->options([
                                'temps plein' => 'Temps plein',
                                'temps partiel' => 'Temps partiel',
                                'occasionnel' => 'Occasionnel',
                            ])
                            ->default('temps plein')
                            ->required(),

                        Select::make('statut')
                            ->label('Statut')
                            ->options([
                                'disponible' => 'Disponible',
                                'occupé' => 'Occupé',
                            ])
                            ->default('disponible')
                            ->required(),

                        TextInput::make('adresse')->label('Adresse complète'),
                        Toggle::make('is_badges')->label('Agent certifié')->default(false),
                    ]),

                Section::make('Assignation')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('recommended_by')
                            ->label('Client assigné')
                            ->relationship('recommendedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->nullable()
                            ->placeholder('Aucun client assigné'),

                        DatePicker::make('recommended_at')
                            ->label('Date d’assignation')
                            ->visible(fn ($get) => filled($get('recommended_by')))
                            ->required(fn ($get) => filled($get('recommended_by'))),
                    ]),
            ]);
    }
}
