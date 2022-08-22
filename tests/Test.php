<?php

require __DIR__ . '/../src/pdobox.php';

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
	public function testGetOptions()
	{
		$GLOBALS['argv'] = [$GLOBALS['argv'][0]];
		$this->assertEquals([], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--'];
		$this->assertEquals([], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c'];
		$this->assertEquals(['c' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c='];
		$this->assertEquals(['c' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-cd'];
		$this->assertEquals(['c' => false, 'd' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '-d'];
		$this->assertEquals(['c' => false, 'd' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '-c', '-d'];
		$this->assertEquals(['c' => [false, false], 'd' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '-d', '-d'];
		$this->assertEquals(['c' => false, 'd' => [false, false]], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-cde'];
		$this->assertEquals(['c' => false, 'd' => false, 'e' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-cd='];
		$this->assertEquals(['c' => false, 'd' => false, '=' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-cf', 'foo'];
		$this->assertEquals(['c' => false, 'f' => 'foo'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--foo'];
		$this->assertEquals(['foo' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--foo', 'bar'];
		$this->assertEquals(['foo' => 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--foo', '--bar'];
		$this->assertEquals(['foo' => false, 'bar' => false], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--foo', 'bar', 'baz'];
		$this->assertEquals(['foo' => 'bar', 'baz'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '--foo=bar', 'baz'];
		$this->assertEquals(['foo' => 'bar', 'baz'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c=foo'];
		$this->assertEquals(['c' => 'foo'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], 'foo', 'bar'];
		$this->assertEquals(['foo', 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', 'foo'];
		$this->assertEquals(['c' => 'foo'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', 'foo', 'bar'];
		$this->assertEquals(['c' => 'foo', 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-cf', 'foo', 'bar'];
		$this->assertEquals(['c' => false, 'f' => 'foo', 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], 'foo', '-c=bar', 'baz'];
		$this->assertEquals(['c' => 'bar', 'foo', 'baz'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', 'foo', '-c', 'bar'];
		$this->assertEquals(['c' => ['foo', 'bar']], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '--', 'foo', 'bar'];
		$this->assertEquals(['c' => '--', 'foo', 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', 'foo', '-c', 'bar', 'baz'];
		$this->assertEquals(['c' => ['foo', 'bar'], 'baz'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], 'foo', '--', '-c', 'bar', 'baz'];
		$this->assertEquals(['foo', '-c', 'bar', 'baz'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '--', '--', '-c', 'foo', 'bar'];
		$this->assertEquals(['c' => '--', '-c', 'foo', 'bar'], GetOptions::fetch());
		$GLOBALS['argv'] = [$GLOBALS['argv'][0], '-c', '--', '-c', 'foo', 'bar', 'baz'];
		$this->assertEquals(['c' => ['--', 'foo'], 'bar', 'baz'], GetOptions::fetch());
	}
}
