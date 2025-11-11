<?php

namespace App\Filament\Resources\AdminLinkClickReportResource\Pages;

use App\Filament\Resources\AdminLinkClickReportResource;
use Filament\Resources\Pages\Page;

class Reports extends Page
{
    protected static string $resource = AdminLinkClickReportResource::class;

    protected static string $view = 'filament.resources.admin-link-click-report-resource.pages.reports';
}
