<?php

namespace App\Command;

use App\Services\SearchService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:search:run',
    description: 'Launch search criteria',
)]
class SearchCommand extends Command
{
    public function __construct(
        private readonly SearchService $searchService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->searchService->run();
        $output->write('<info>Search launched</info>');
        return Command::SUCCESS;
    }
}