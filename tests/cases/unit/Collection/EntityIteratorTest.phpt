<?php

/**
 * @testCase
 */

namespace NextrasTests\Orm\Collection;

use Mockery;
use Nextras\Orm\Collection\EntityIterator;
use NextrasTests\Orm\TestCase;
use Tester\Assert;

$dic = require_once __DIR__ . '/../../../bootstrap.php';


class EntityIteratorTest extends TestCase
{

	public function testSimpleArray()
	{
		$data = [Mockery::mock(), Mockery::mock(), Mockery::mock()];
		$data[0]->shouldReceive('getRawValue')->with('id')->andReturn(123);
		$data[1]->shouldReceive('getRawValue')->with('id')->andReturn(321);
		$data[2]->shouldReceive('getRawValue')->with('id')->andReturn(456);

		$iterator = new EntityIterator($data);
		Assert::same(3, count($iterator));

		$data[0]->shouldReceive('setPreloadContainer')->twice()->with($iterator);
		$data[1]->shouldReceive('setPreloadContainer')->twice()->with($iterator);
		$data[2]->shouldReceive('setPreloadContainer')->twice()->with($iterator);

		Assert::same($data, iterator_to_array($iterator));
		Assert::same($data, iterator_to_array($iterator)); // check iterator rewind
		Assert::same([123, 321, 456], $iterator->getPreloadValues('id'));
	}


	public function testIteratorOverflow()
	{
		$data = [Mockery::mock()];
		$data[0]->id = 123;

		$iterator = new EntityIterator($data);
		$data[0]->shouldReceive('setPreloadContainer')->once()->with($iterator);
		Assert::same($data, iterator_to_array($iterator));

		$iterator->next();
		Assert::null($iterator->current());
	}


	public function testSubarrayIterator()
	{
		$data = [ 10 => [Mockery::mock()], 12 => [Mockery::mock(), Mockery::mock()] ];
		$data[10][0]->shouldReceive('getRawValue')->with('id')->andReturn(123);
		$data[12][0]->shouldReceive('getRawValue')->with('id')->andReturn(321);
		$data[12][1]->shouldReceive('getRawValue')->with('id')->andReturn(456);

		$iterator = new EntityIterator($data);
		$iterator->setDataIndex(12);

		Assert::same(2, count($iterator));

		$data[12][0]->shouldReceive('setPreloadContainer')->once()->with($iterator);
		$data[12][1]->shouldReceive('setPreloadContainer')->once()->with($iterator);

		Assert::same($data[12], iterator_to_array($iterator));
		Assert::same([123, 321, 456], $iterator->getPreloadValues('id'));

		$iterator->setDataIndex(13);
		Assert::same(0, count($iterator));
		Assert::same([], iterator_to_array($iterator));
	}
}


$test = new EntityIteratorTest($dic);
$test->run();
