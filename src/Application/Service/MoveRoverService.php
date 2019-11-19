<?php
namespace Application\Service;

use Application\Contract\ApplicationRequestInterface;
use Application\Contract\ApplicationServiceInterface;
use Application\Contract\RoverDataTransformerInterface;
use Domain\Model\Exception\NoRoverWithGivenIdException;
use Domain\Model\Contract\RoverRepositoryInterface;
use Domain\Model\ValueObject\Instruction;
use Domain\Model\ValueObject\RoverId;

class MoveRoverService implements ApplicationServiceInterface
{
    private $roverRepository;
    private $roverDataTransformer;

    public function __construct(RoverRepositoryInterface $roverRepository, RoverDataTransformerInterface $roverDataTransformer)
    {
        $this->roverRepository = $roverRepository;
        $this->roverDataTransformer = $roverDataTransformer;
    }

    /**
     * @param MoveRoverRequest $request
     * @throws NoRoverWithGivenIdException
     */
    public function execute(ApplicationRequestInterface $request)
    {
        $rover = $this->roverRepository->ofId(new RoverId($request->id()));

        if(!$rover) {
            throw new NoRoverWithGivenIdException();
        }

        $instructions = $request->instructions();
        for($i = 0; $i < strlen($instructions); $i++ ) {
            $newPosition = $rover->applyInstruction( new Instruction($instructions[$i]) );
            if( $newPosition && !$this->roverRepository->ofPosition($newPosition)) {
                $rover->move($newPosition);
            }
        }
        $this->roverDataTransformer->write($rover);
        return $this->roverDataTransformer->read();
    }
}