<?php

namespace App\Filament\Resources\DashboardResource\Pages; // İdealde App\Filament\Pages olmalı

// use App\Filament\Resources\DashboardResource; // Resource'a bağlı olmayacak
use Filament\Pages\Page; // Page sınıfını kullanacağız
use App\Livewire\AdminDashboardStats; // Livewire bileşenimizi import ediyoruz

class Dashboard extends Page
{
    // protected static string $resource = DashboardResource::class; // Resource'a bağlı olmayacak

    protected static ?string $navigationIcon = 'heroicon-o-home'; // Navigasyon ikonu
    protected static ?string $navigationLabel = 'Anasayfa'; // Navigasyon etiketi (nullable string olarak düzeltildi)
    protected static ?string $title = 'Admin Paneli Anasayfa'; // Sayfa başlığı

    protected static string $view = 'filament.resources.dashboard-resource.pages.dashboard';
}
