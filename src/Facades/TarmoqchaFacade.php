<?php  
namespace Root0x7\Tarmoqcha\Facades;

use Illuminate\Support\Facades\Facade;


class TarmoqchaFacade extends Facade{
	protected static function getFacadeAccessor()
	{
		return 'localtunnel';
	}
}