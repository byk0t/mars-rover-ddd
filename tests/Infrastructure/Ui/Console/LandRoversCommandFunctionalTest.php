<?php
declare(strict_types=1);

namespace Application\Infrastructure\Ui\Console;

use PHPUnit\Framework\TestCase;

class LandRoversCommandFunctionalTest extends TestCase
{
    public function testConsoleApp(): void
    {
        exec('php '.__DIR__.'/../../../../bin/console land-rovers --file=./tests/Fixtures/testdata.txt', $out);
        $this->assertCount(2, $out);
        $this->expectOutputString("1 3 N");
        echo $out[0];
    }
}