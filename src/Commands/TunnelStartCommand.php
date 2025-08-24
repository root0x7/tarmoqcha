<?php

namespace Root0x7\Tarmoqcha\Commands;

use Illuminate\Console\Command;
use Root0x7\Tarmoqcha\Facades\Tarmoqcha;

class TunnelStartCommand extends Command
{
    protected $signature = 'tunnel:start 
                            {--port=8000 : Local port to expose} 
                            {--subdomain= : Custom subdomain}';
    
    protected $description = 'Start local tunnel to expose Laravel app';

    public function handle()
    {
        $port = $this->option('port');
        $subdomain = $this->option('subdomain');

        $this->info("Tunnel ochilmoqda port {$port} uchun...");

        try {
            $publicUrl = Tarmoqcha::start($port, $subdomain);
            
            $this->info("✅ Tunnel muvaffaqiyatli ochildi!");
            $this->line("🌐 Public URL: <comment>{$publicUrl}</comment>");
            $this->line("📍 Local URL: <comment>http://127.0.0.1:{$port}</comment>");
            $this->line("");
            $this->warn("Tunnel to'xtatish uchun Ctrl+C bosing yoki 'php artisan tunnel:stop' ishga tushiring.");

            // Tunnel active holatda saqlab qolish
            while (true) {
                sleep(1);
            }

        } catch (\Exception $e) {
            $this->error("❌ Xatolik: " . $e->getMessage());
            return 1;
        }
    }
}
