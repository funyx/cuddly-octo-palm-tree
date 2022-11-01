<?php

namespace Funyx\Waveform\Tests;

use PHPUnit\Framework\TestCase;
use Waveform\LineParser;
use Waveform\Log;

/**
 * @property \Waveform\LineParser $parser
 */
class LineParserTest extends TestCase
{
	protected function setUp(): void
	{
		$this->parser = new LineParser(new Log(''));
	}

	public function test_start(): void
	{
		$start = $this->parser->getStart('[silencedetect @ 0x7fa7edd0c160] silence_start: 1.84');
		$this->assertEquals(1.84, $start);

		$start = $this->parser->getStart('detect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_du');
		$this->assertEquals(0.00, $start);
	}

	public function test_end(): void
	{
		$end = $this->parser->getEnd('[silencedetect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_duration: 2.64');
		$this->assertEquals(4.48, $end);

		$end = $this->parser->getEnd('tect @ 0x7fa7edd0c160] silence_start: 1.84');
		$this->assertEquals(0.00, $end);
	}
}
