<?php

namespace Waveform;

use Closure;

class Kernel
{

	public ChannelBundle $channels;
	private array $callbacks = [];

	/**
	 * @throws \Waveform\Exception
	 */
	public function __construct( array $channels, $auto_init = true )
	{
		$this->channels = new ChannelBundle();
		foreach ($channels as $name => $url) {
			$this->channels->add($name, $url);
		}
		if ($auto_init) {
			$this->init();
		}
	}

	public function init(): void
	{
		$this->channels->parseLogs();
	}

	public function bind($key, Closure $cb): void
	{
		$this->callbacks[$key] = $cb;
	}

	public function getOutput(): array
	{
		return [
			...$this->channels->getActiveSeries(),
			...$this->getBinds()
		];
	}

	private function getBinds(): array
	{
		$t = [];
		foreach ($this->callbacks as $name => $callback) {
			$t[$name] = $callback();
		}
		return $t;
	}

}
