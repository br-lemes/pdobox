#!/usr/bin/php
<?php

class GetOptions
{
	static private $result;
	static private $stop;
	static private $key;

	static public function fetch()
	{
		self::$result = [];
		self::$stop = false;
		self::$key = null;
		for ($i = 1; $i < count($GLOBALS['argv']); $i++) {
			$arg = $GLOBALS['argv'][$i];
			if (self::$stop) {
				self::$result[] = $arg;
				continue;
			}
			if (self::parseDoubleDash($arg)) {
				continue;
			}
			if (self::parseLong($arg)) {
				continue;
			}
			if (self::parseShort($arg)) {
				continue;
			}
			if (self::$key) {
				self::updateResult($arg);
				self::$key = null;
				continue;
			}
			self::$result[] = $arg;
		}
		if (self::$key) {
			self::updateResult(false);
		}
		return self::$result;
	}

	static private function parseDoubleDash($arg)
	{
		if ($arg === '--') {
			if (self::$key) {
				self::updateResult($arg);
				self::$key = null;
				return true;
			}
			self::$stop = true;
			return true;
		}
		return false;
	}

	static private function parseLong($arg)
	{
		if ($arg[0] === '-' && $arg[1] === '-') {
			if (self::$key) {
				self::updateResult(false);
			}
			$exploded = explode('=', ltrim($arg, '-'));
			self::$key = $exploded[0];
			if (isset($exploded[1]) && $exploded[1]) {
				self::updateResult($exploded[1]);
				self::$key = null;
			}
			return true;
		}
		return false;
	}

	static private function parseShort($arg)
	{
		if ($arg[0] === '-') {
			if (isset($arg[2]) && $arg[2] === '=') {
				self::$key = $arg[1];
				self::updateResult(substr($arg, 3));
				self::$key = null;
				return true;
			}
			if (self::$key) {
				self::updateResult(false);
			}
			self::$key = $arg[1];
			for ($i = 2; $i < strlen($arg); $i++) {
				if (self::$key) {
					self::updateResult(false);
					self::$key = $arg[$i];
				}
			}
			return true;
		}
		return false;
	}

	static private function updateResult($value)
	{
		if (!isset(self::$result[self::$key])) {
			self::$result[self::$key] = $value;
		} else {
			if (!is_array(self::$result[self::$key])) {
				self::$result[self::$key] = [self::$result[self::$key]];
			}
			self::$result[self::$key][] = $value;
		}
	}
}
