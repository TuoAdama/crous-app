<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\IdToolService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use Exception;

#[AsCommand(name: 'app:id-tool', description: 'Hello PhpStorm')]
class IdToolCommand extends Command
{
    public function __construct(
        private readonly IdToolService $idToolService,
        private readonly LoggerInterface $idToolLogger,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $idTool = $this->idToolService->updateIdTool();
            $this->idToolLogger->info('ID tool updated successfully', [
                'id' => $idTool,
            ]);
        } catch (Exception $e) {
            $this->idToolLogger->error('Error updating ID tool', [
                'exception' => $e,
            ]);
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
