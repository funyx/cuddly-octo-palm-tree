<?php

namespace Funyx\Waveform\Tests;

use Waveform\Kernel;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
	/**
	 * @throws \Waveform\Exception
	 */
	public function test_app(): void
	{
		$app = new Kernel([
			'user' => 'https://raw.githubusercontent.com/jiminny/join-the-team/master/assets/user-channel.txt',
			'customer' => 'https://raw.githubusercontent.com/jiminny/join-the-team/master/assets/customer-channel.txt',
		]);
		$app->bind('longest_user_monologue', fn() => $app->channels->get('user')->getLongestMonologue());
		$app->bind('longest_customer_monologue', fn() => $app->channels->get('customer')->getLongestMonologue());
		$app->bind('user_talk_percentage', fn() => $app->channels->get('user')->getActivityPercentage());

		$output = $app->getOutput();

		$this->assertArrayHasKey('user', $output);
		$this->assertNotEmpty($output['user']);
		$this->assertArrayHasKey('customer', $output);
		$this->assertNotEmpty($output['customer']);
		$this->assertArrayHasKey('longest_user_monologue', $output);
		$this->assertIsFloat($output['longest_user_monologue']);
		$this->assertArrayHasKey('longest_customer_monologue', $output);
		$this->assertIsFloat($output['longest_customer_monologue']);
		$this->assertArrayHasKey('user_talk_percentage', $output);
		$this->assertIsFloat($output['user_talk_percentage']);
	}
}
