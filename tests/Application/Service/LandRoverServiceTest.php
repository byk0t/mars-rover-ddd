<?php
declare(strict_types=1);

namespace Application\Service;

use Application\DataTransformer\RoverDtoDataTransformer;
use Infrastructure\Persistence\InMemory\InMemoryRoverRepository;
use PHPUnit\Framework\TestCase;

class LandRoverServiceTest extends TestCase
{
    private $successfulInput   = '1 2 N 5 5';
    private $unSuccessfulInput = '5 6 N 5 5';
    private $landRoverService;

    protected function setUp(): void
    {
        $this->landRoverService = new LandRoverService(new InMemoryRoverRepository(), new RoverDtoDataTransformer());
    }

    public function testSuccessfulLanding(): void
    {
        $roverDto = $this->landRoverService->execute(
          LandRoverRequest::fromString($this->successfulInput)
        );
        $this->assertTrue(is_array($roverDto));
        $this->assertTrue($roverDto['landed']);
    }

    public function testUnSuccessfulLanding(): void
    {
        $roverDto = $this->landRoverService->execute(
            LandRoverRequest::fromString($this->unSuccessfulInput)
        );
        $this->assertTrue(is_array($roverDto));
        $this->assertFalse($roverDto['landed']);
    }
}