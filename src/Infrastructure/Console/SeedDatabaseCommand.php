<?php

namespace App\Infrastructure\Console;

use App\Infrastructure\Database\Seeders\ClientSeeder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:seed-database',
    description: 'Seeds the database with test data.'
)]
class SeedDatabaseCommand extends Command
{
    private ClientSeeder $clientSeeder;

    public function __construct(ClientSeeder $clientSeeder)
    {
        $this->clientSeeder = $clientSeeder;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Seeds the database with test data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clientSeeder->run();

        $output->writeln('Database seeded!');

        return Command::SUCCESS;
    }
}