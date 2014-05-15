<?php
class ArithmeticTest extends PHPUnit_Framework_TestCase {
	public function getExpression() {
		return 0;
	}
	public function getResult() {
		return 0;
	}
	public function test() {
		$this->assertEquals( $this->getResult(), $this->getExpression() );
	}
}

