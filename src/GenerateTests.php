<?php

namespace De\Idrinth\TestGenerator;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GenerateTests extends Command
{
    protected function configure()
    {
        $this->setName('generate-tests')
            ->addOption('mode', 'm', InputArgument::OPTIONAL, "Replace | Skip | Move")
            ->addOption('dir', 'd', InputArgument::OPTIONAL, 'Directory which contains composer.json', null)
            ->addOption('output', 'o', InputArgument::OPTIONAL, 'Output-Directory for generated files', null);
        $this->setApplication((new Application(
            'PHPUnit-Test-Generator',
            '1.0.0'
        ))->run());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Controller::init()->run();
    }
}
