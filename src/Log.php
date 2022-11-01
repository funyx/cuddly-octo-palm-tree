<?php

namespace Waveform;

class Log
{
	/**
	 * @var \Waveform\LineParser
	 */
	public LineParser $parser;
	private array $active_series = [];
	private float $longest_monologue = 0.00;
	private float $activity_total = 0.00;
	private float $duration = 0.00;


	/**
	 * @throws \Waveform\Exception
	 */
	public function __construct( private readonly mixed $input )
	{
		$this->parser = new LineParser($this);
	}

	/**
	 * @throws \Waveform\Exception
	 */
	public static function url( string $url ): Log
	{
		$contents = file_get_contents($url);
		if ($contents) {
			return new self($contents);
		}
	}

	public function parse(): void
	{
		$last_active = 0.00;
		foreach ($this->getEntries() as $entry) {
			if (($start = $this->parser->getStart($entry)) > 0) {
				$this->active_series[] = [
					$last_active,
					$start
				];
				$this->setLongestMonologue($last_active, $start);
				$this->addActivityTotal($last_active, $start);
				continue;
			}
			if (($end = $this->parser->getEnd($entry)) > 0) {
				$last_active = $end;
				$this->duration = $end;
			}
		}
	}

	public function getInput(): string
	{
		return $this->input;
	}

	public function getEntries(): array
	{
		return $this->parser->entries;
	}

	public function getActiveSeries(): array
	{
		return $this->active_series;
	}

	public function getLongestMonologue(): float
	{
		return $this->longest_monologue;
	}

	private function setLongestMonologue( float $start, float $end ): void
	{
		if (($diff = $end - $start) > $this->longest_monologue) {
			$this->longest_monologue = $diff;
		}
	}

	private function addActivityTotal( float $start, float $end ): void
	{
		$this->activity_total += ($end - $start);
	}

	public function getActivityPercentage(): float
	{
		return ($this->activity_total / $this->duration) * 100;
	}
}
