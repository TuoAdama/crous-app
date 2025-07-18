<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\IdToolService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:id-tool', description: 'Hello PhpStorm')]
class IdToolCommand extends Command
{
    public function __construct(
        private readonly IdToolService $idToolService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);
        $id = $this->idToolService->getIdTool();
        $end = microtime(true);
        dd([
            'id' => $id,
            'execution_time' => ($end - $start) . ' seconds',
            'screenshot' => 'screenshot/screenshot.png',
        ]);
        return Command::SUCCESS;
    }
}
