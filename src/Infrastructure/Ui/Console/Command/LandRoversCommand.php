<?php
declare(strict_types=1);

namespace Infrastructure\Ui\Console\Command;

use Application\Service\InitAreaRequest;
use Application\Service\LandedRoversStatusRequest;
use Application\Service\MoveRoverRequest;
use Infrastructure\IO\IOHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use Application\Service\LandRoverRequest;

class LandRoversCommand extends Command
{
    private $fileHandle = null;
    private $useFileInput = false;

    protected static $defaultName = 'land-rovers';

    protected function configure()
    {
        $this->addOption(
            'file',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Disable interactive questions and use a data from given file path'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->openFileIfNeeded($input);

        $container = $this->getApplication()->container();
        $landingAreaSize = $this->inputLandingAreaSize($input, $output);

        $landRoverService = $container->get('land_rover_service');
        $moveRoverService = $container->get('move_rover_service');

        while ( ($position = $this->inputRoverPosition($input, $output)) ) {
            $instructions = $this->inputRoverMoves($input, $output);
            $roverDto = $landRoverService->execute( LandRoverRequest::fromString($position.' '.$landingAreaSize) );
            $moveRoverService->execute( MoveRoverRequest::fromString($roverDto['id'].' '.$instructions));
        }

        $this->closeFileIfNeeded();

        $statuses = $container->get('landed_rovers_status_service')->execute( new LandedRoversStatusRequest() );
        foreach ($statuses as $s) {
            $output->writeln($s);
        }
    }

    private function input(InputInterface $input, OutputInterface $output, string $question, string $regexp)
    {
        $helper = $this->getHelper('question');
        $question = new Question($question);
        $question->setValidator(function ($answer) use ($regexp) {
            if(!is_string($answer))
                return '';
            if(!preg_match($regexp, $answer)) {
                throw new \RuntimeException('Wrong input data');
            }
            return $answer;
        });
        $question->setMaxAttempts(2);
        $data = $helper->ask($input, $output, $question);
        return $data;
    }

    private function inputLandingAreaSize(InputInterface $input, OutputInterface $output): string
    {
        if($this->fileInput()) {
            $data = $this->readNextLine();
        } else {
            $data = $this->input($input, $output, 'Landing LandingArea Top Right Coordinates <x> <y>: ', '/^\d \d$/');
        }
        return $data;
    }

    private function inputRoverPosition(InputInterface $input, OutputInterface $output)
    {
        if($this->fileInput()) {
            $data = $this->readNextLine();
        } else {
            $data = $this->input($input, $output,
                'Next rover\'s landing position <x> <y> <Direction>, <Enter> to stop: ',
                '/^\d \d (N|W|E|S)$/'
            );
        }
        return $data;
    }

    private function inputRoverMoves(InputInterface $input, OutputInterface $output)
    {
        if($this->fileInput()) {
            $data = $this->readNextLine();
        } else {
            $data = $this->input($input, $output, 'Next rover\'s moves <M|R|L>... : ', '/^(L|R|M)+$/');
        }
        return $data;
    }

    /**
     * @todo Move File Operations to a trait
     * @return bool
     */
    private function fileInput(): bool {
        return $this->useFileInput;
    }

    private function openFileIfNeeded(InputInterface $input): void {
        if($input->getOption('file') && file_exists($input->getOption('file'))) {
            $this->fileHandle = fopen($input->getOption('file'), 'r');
            $this->useFileInput = true;
        }
    }

    private function closeFileIfNeeded(): void {
        if($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
            $this->useFileInput = false;
        }
    }

    private function readNextLine() {
        return IOHelper::safeReadLine($this->fileHandle);
    }

}