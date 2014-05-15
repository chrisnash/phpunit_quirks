<?php
require_once( dirname( __FILE__ ) . '/ArithmeticTest.php' );

class AdditionTest extends ArithmeticTest {
	public function getExpression() {
		return 3 + 2;
	}
	public function getResult() {
		return 5;
	}
}
