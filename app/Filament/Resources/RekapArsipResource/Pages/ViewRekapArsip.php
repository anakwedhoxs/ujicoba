<?php


namespace App\Filament\Resources\RekapArsipResource\Pages;


use App\Filament\Resources\RekapArsipResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;


class ViewRekapArsip extends ViewRecord
{
    protected static string $resource = RekapArsipResource::class;


    public function form(Form $form): Form
    {
        return $form->schema([]);
    }
}



