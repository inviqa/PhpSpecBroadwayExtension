<?php

namespace Inviqa\PhpspecBroadwayExtension\Console\Command;

use PhpSpec\Console\Command\DescribeCommand as ParentCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command line command responsible to signal to generators we will need to
 * generate a new spec
 */
class DescribeCommand extends ParentCommand
{
    protected function configure()
    {
        parent::configure();

        $this->addOption('acts-on', 'a', InputOption::VALUE_REQUIRED, 'ActsOn');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->getContainer()->setParam(
            'broadway.acts-on',
            str_replace('/', '\\', $input->getOption('acts-on')
            )
        );

        parent::execute($input, $output);
    }
}
