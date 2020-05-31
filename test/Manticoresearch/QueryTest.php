<?php declare(strict_types = 1);

namespace Manticoresearch\Test;

use Manticoresearch\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{

    /** @var \Manticoresearch\Query */
    private $query;

    public function setUp(): void
    {
        parent::setUp();

        $this->query = new Query();
    }

    public function testNoParams(): void
    {
        $this->assertEquals([], $this->query->toArray());
    }

    public function testParamsNoNesting(): void
    {
        $this->query->add('a', 1);
        $this->query->add('b', 2);
        $this->query->add('c', 3);
        $this->assertEquals([
            'a' => 1,
            'b' => 2,
            'c' =>3,
        ], $this->query->toArray());
    }

    public function testParamsWithNesting(): void
    {
        $this->query->add('a', 1);
        $subParams = ['b' => 2, 'c' => 3];
        $this->query->add('x', $subParams);
        $this->assertEquals([
            'a' => 1,
            'x' => [
                'b' => 2,
                'c' =>3,
            ],
        ], $this->query->toArray());
    }

    public function testParamsWithNull(): void
    {
        $this->query->add('a', 1);
        $subParams = ['b' => null];
        $this->query->add('x', $subParams);
        $this->assertEquals([
            'a' => 1,
            'x' => null,
        ], $this->query->toArray());
    }

    public function testWithParamsAndSubQuery(): void
    {
        $this->query->add('a', 1);
        $subquery = new Query();
        $subquery->add('b', 2);
        $this->query->add('x', $subquery);
        $this->assertEquals(['a' => 1, 'x' => ['b' => 2]], $this->query->toArray());
    }

}
