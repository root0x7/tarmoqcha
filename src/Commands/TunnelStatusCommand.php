<?php

namespace Root0x7\Tarmoqcha\Commands;

use Illuminate\Console\Command;
use Root0x7\Tarmoqcha\Facades\Tarmoqcha;

class TunnelStatusCommand extends Command
{
    protected $signature = 'tunnel:status';
    protected $description = 'Show tunnel status';

    public function handle()
    {
        $status = Tarmoqcha::getStatus();
        
        if ($status['active']) {
            $this->info("✅ Tunnel faol");
            $this->line("🌐 Public URL: <comment>{$status['public_url']}</comment>");
        } else {
            $this->warn("⏸️  Tunnel faol emas");
        }
    }
}