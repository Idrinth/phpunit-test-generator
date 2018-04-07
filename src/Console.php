<?php
namespace De\Idrinth\TestGenerator;
use De\Idrinth\TestGenerator\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class Console
{


    /**
     * @var Application
     */
    protected $application;

    public function __construct()
    {
        $this->application = $this->init();
    }

    /**
     * @return Application
     */
    private function init() {
        return (new Application('PHPUnit-Test-Generator', '1.0.0'))
            ->register('generate-tests')
            ->addOption('mode', 'm', InputArgument::OPTIONAL, "Replace | Skip | Move")
            ->addOption('dir', 'd', InputArgument::OPTIONAL, 'Directory which contains composer.json', null)
            ->addOption('output', 'o', InputArgument::OPTIONAL, 'Output-Directory for generated files', null)
            ->setCode(
                function(InputInterface $input, OutputInterface $output) {
                    Controller::init()->run();
                })
            ->getApplication()
            ->setDefaultCommand('generate-tests', true);
    }


    /**
     * @return Application
     */
    public function getApplication(){
        return $this->application;
    }

    public function run(){
        $this->application->run();
    }

}