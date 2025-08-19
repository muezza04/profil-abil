<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ExportStatic extends Command
{
    protected $signature = 'export:static';
    protected $description = 'Export Blade views to static HTML files';

    public function handle()
    {
        // Daftar halaman Blade yang ingin diekspor
        $pages = [
            // 'home'        => 'index.html',
            'about'       => 'about.html',
            'certificate' => 'certificate.html',
        ];

        $outputDir = public_path('static'); // folder output
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        foreach ($pages as $view => $fileName) {
            if (View::exists($view)) {
                $html = View::make($view)->render();

                // Ganti path asset laravel menjadi relatif
                $html = str_replace('{{ asset(\'', '', $html);
                $html = str_replace('\') }}', '', $html);

                File::put($outputDir.'/'.$fileName, $html);
                $this->info("✅ Exported: {$fileName}");
            } else {
                $this->warn("⚠️ View '{$view}' tidak ditemukan, dilewati.");
            }
        }

        $this->info('🚀 Semua halaman berhasil diexport menjadi HTML statis!');
    }
}
