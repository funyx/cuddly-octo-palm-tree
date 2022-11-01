<?php

namespace Waveform;

class LineParser
{
	/**
	 * @var \Waveform\Log
	 */
	public Log $log;
	public array $entries;

	public function __construct( Log $log )
	{
		$this->log = $log;
		$this->entries = array_filter(explode(PHP_EOL, $log->getInput()));
	}

	public function getStart( string $line ): float
	{
		$m = [];
		$r = 0.00;
		$check = preg_match('/\d*(?:\.\d+)$/', $line, $m);
		if ($check && str_contains($line, 'silence_start')) {
			$r = (float) $m[0];
		}

		return $r;
	}

	public function getEnd( string $line ): float
	{
		$m = [];
		$r = 0.00;
		$line = trim(explode('|', $line)[0]);
		$check = preg_match('/\d*(?:\.\d+)$/', $line, $m);
		if ($check && str_contains($line, 'silence_end')) {
			$r = (float) $m[0];
		}

		return $r;
	}
}
