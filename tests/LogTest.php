<?php

namespace Funyx\Waveform\Tests;

use PHPUnit\Framework\TestCase;
use Waveform\Log;

/**
 * @property \Waveform\LineParser $parser
 */
class LogTest extends TestCase
{
	private string $dataset = '
	[silencedetect @ 0x7fa7edd0c160] silence_start: 1.84
	[silencedetect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_duration: 2.64
	[silencedetect @ 0x7fa7edd0c160] silence_start: 26.928';

	/**
	 * @throws \Waveform\Exception <- support - strings.
	 */
	protected function setUp(): void
	{
		$this->log = new Log($this->dataset);
	}

	public function test_entries(): void
	{
		$e = $this->log->getEntries();
		$this->assertIsArray($e);
		$this->assertNotEmpty($e);
		$this->assertCount(3, $e);
	}

	public function test_parsed_series(): void
	{
		$this->log->parse();
		$s = $this->log->getActiveSeries();
		$this->assertIsArray($s);
		$this->assertNotEmpty($s);
		$this->assertCount(2, $s);
		$this->assertEquals(0.00, $s[0][0], 'start from 0');
		$this->assertEquals(1.84, $s[0][1], 'talks until 1.84');
	}
}
