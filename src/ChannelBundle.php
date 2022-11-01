<?php

namespace Waveform;

class ChannelBundle
{

	protected array $names;
	protected array $logs;
	/**
	 * @throws \Waveform\Exception
	 */
	public function add( int|string $name, string $data ): void
	{
		if(filter_var($data, FILTER_VALIDATE_URL) === FALSE){
			$this->addLog($name, $data);
		}else{
			$this->addUrl($name, $data);
		}

	}
	/**
	 * @throws \Waveform\Exception
	 */
	private function addLog( int|string $name, string $data ): void
	{
		$this->names[] = $name;
		$this->logs[] = new Log($data);
	}

	/**
	 * @throws \Waveform\Exception
	 */
	private function addUrl( int|string $name, string $url ): void
	{
		$this->names[] = $name;
		$this->logs[] = Log::url($url);
	}

	public function parseLogs(): void
	{
		foreach ($this->logs as $log){
			if(is_a($log, Log::class)){
				$log->parse();
			}
		}
	}

	private function getLog($name): Log
	{
		return $this->logs[array_keys($this->names, $name)[0]];
	}

	public function getActiveSeries(): array
	{
		$a = [];
		foreach ($this->names as $name) {
			$a[$name] = $this->getLog($name)->getActiveSeries();
		}
		return $a;
	}

	public function get( string $name ): Log
	{
		return $this->getLog($name);
	}
}
