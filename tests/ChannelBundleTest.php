<?php

namespace Funyx\Waveform\Tests;

use PHPUnit\Framework\TestCase;
use Waveform\ChannelBundle;
use Waveform\Log;

/**
 * @property \Waveform\LineParser $parser
 */
class ChannelBundleTest extends TestCase
{
	private array $dataset = [
		'user' => '
			[silencedetect @ 0x7fbfbbc076a0] silence_start: 3.504
			[silencedetect @ 0x7fbfbbc076a0] silence_end: 6.656 | silence_duration: 3.152
			[silencedetect @ 0x7fbfbbc076a0] silence_start: 14
			[silencedetect @ 0x7fbfbbc076a0] silence_end: 19.712 | silence_duration: 5.712
			[silencedetect @ 0x7fbfbbc076a0] silence_start: 20.144
			[silencedetect @ 0x7fbfbbc076a0] silence_end: 27.264 | silence_duration: 7.12
			[silencedetect @ 0x7fbfbbc076a0] silence_start: 36.528
			[silencedetect @ 0x7fbfbbc076a0] silence_end: 41.728 | silence_duration: 5.2
			[silencedetect @ 0x7fbfbbc076a0] silence_start: 47.28
			[silencedetect @ 0x7fbfbbc076a0] silence_end: 49.792 | silence_duration: 2.512',
		'customer' => '
			[silencedetect @ 0x7fa7edd0c160] silence_start: 1.84
			[silencedetect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_duration: 2.64
			[silencedetect @ 0x7fa7edd0c160] silence_start: 26.928
			[silencedetect @ 0x7fa7edd0c160] silence_end: 29.184 | silence_duration: 2.256
			[silencedetect @ 0x7fa7edd0c160] silence_start: 29.36
			[silencedetect @ 0x7fa7edd0c160] silence_end: 31.744 | silence_duration: 2.384
			[silencedetect @ 0x7fa7edd0c160] silence_start: 56.624
			[silencedetect @ 0x7fa7edd0c160] silence_end: 58.624 | silence_duration: 2
			[silencedetect @ 0x7fa7edd0c160] silence_start: 66.992
			[silencedetect @ 0x7fa7edd0c160] silence_end: 69.632 | silence_duration: 2.64',
		'user_url' => 'https://raw.githubusercontent.com/jiminny/join-the-team/master/assets/user-channel.txt',
		'customer_url' => 'https://raw.githubusercontent.com/jiminny/join-the-team/master/assets/customer-channel.txt',
	];
	protected function setUp(): void
	{
		$this->cb = new ChannelBundle();
	}


	/**
	 * @throws \Waveform\Exception
	 */
	public function test_active_series(): void
	{
		$this->cb->add('user', $this->dataset['user']);
		$this->cb->add('customer', $this->dataset['customer']);
		$this->cb->parseLogs();
		$this->assert_active_series();
	}

	/**
	 * @throws \Waveform\Exception
	 */
	public function test_url_active_series(): void
	{
		$this->cb->add('user', $this->dataset['user_url']);
		$this->cb->add('customer', $this->dataset['customer_url']);
		$this->cb->parseLogs();
		$this->assert_active_series();
	}

	public function assert_active_series(): void
	{
		$as = $this->cb->getActiveSeries();
		$this->assertIsArray($as);
		$this->assertNotEmpty($as);
		$this->assertArrayHasKey('user', $as);
		$this->assertIsArray($as['user']);
		$this->assertNotEmpty($as['user']);
		$this->assertArrayHasKey('customer', $as);
		$this->assertIsArray($as['customer']);
		$this->assertNotEmpty($as['customer']);
	}
}
