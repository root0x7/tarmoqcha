<?php

namespace Root0x7\Tarmoqcha\Commands;

use Illuminate\Console\Command;
use Root0x7\Tarmoqcha\Facades\Tarmoqcha;

class TunnelStopCommand extends Command
{
    protected $signature = 'tunnel:stop';
    protected $description = 'Stop active tunnel';

    public function handle()
    {
        Tarmoqcha::stop();
        $this->info("🛑 Tunnel to'xtatildi.");
    }
}
