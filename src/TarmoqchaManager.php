<?php 

namespace Root0x7\Tarmoqcha;



use GuzzleHttp\Client;
use React\Socket\Connector;
use React\Stream\WritableResourceStream;


class TarmoqchaManager{
	protected $config;
	protected $client;
	protected $tunnelProcess;
	protected $publicUrl;

	public function __construct(array $config)
	{
		$this->config = $config;
		$this->client = new Client();
	}

	public function start($port= 8080, $subdomain = null){
		$tunnelServer = $this->config['server_url'];

		$subdomain = $subdomain ?? $this->generateRandomSubdomain();
		try{
			$response = $this->client->post($tunnelServer.'/api/tunnels',[
				'json'=>[
					'subdomain'=>$subdomain,
					'port'=>$port,
					'locahost'=>'127.0.0.1'
				]
			]);

			$data = json_decode($response->gerBody(),true);

			$this->publicUrl = $data['public_url'];

			$this->establishTunnel($data['tunnel_id'], $port);

			return $this->publicUrl;
		}catch(\Exception $e){
			throw new \Exception("Tunnel yaratishda xatolik: ".$e->getMessage());
		}
	}

	public function establishTunnel($tunnelId, $localPort){
		$connector = new Connector();

		$connector->connect('tcp://trash.local')->then(function($connection)use($tunnelId,$localPort){
			$connection->write(json_encode([
				'type' => 'register',
				'tunnel_id' => $tunnelId,
				'local_port' => $localPort
			]));

                // Local serverdagi so'rovlarni proxy qilish
			$connection->on('data', function ($data) use ($localPort) {
				$this->proxyRequest(json_decode($data, true), $localPort);
			});
		});
	}

	protected function proxyRequest($requestData, $localPort)
	{
		try {
			$response = $this->client->request(
				$requestData['method'],
				"http://127.0.0.1:{$localPort}" . $requestData['path'],
				[
					'headers' => $requestData['headers'] ?? [],
					'body' => $requestData['body'] ?? null
				]
			);

			return [
				'status' => $response->getStatusCode(),
				'headers' => $response->getHeaders(),
				'body' => (string) $response->getBody()
			];

		} catch (\Exception $e) {
			return [
				'status' => 500,
				'body' => 'Proxy error: ' . $e->getMessage()
			];
		}
	}

	public function stop()
	{
		if ($this->tunnelProcess) {
			proc_terminate($this->tunnelProcess);
			$this->tunnelProcess = null;
			$this->publicUrl = null;
		}
	}

	public function getStatus()
	{
		return [
			'active' => !is_null($this->publicUrl),
			'public_url' => $this->publicUrl,
			'created_at' => now()
		];
	}

	protected function generateRandomSubdomain($length = 8)
	{
		return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
	}
}