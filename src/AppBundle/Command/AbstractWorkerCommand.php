<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractWorkerCommand
 * @package AppBundle\Command
 */
abstract class AbstractWorkerCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritDoc}
     */
    abstract protected function process();

    /**
     * {@inheritDoc}
     */
    abstract protected function prepareServices();

    /**
     * {@inheritDoc}
     */
    protected function getWorkerHeader()
    {
        return 'Start process';
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->showMessage('<info>'.$this->getWorkerHeader().'</info>', false);
        $this->prepareServices();
        while (true) {
            $this->process();
        }
    }

    /**
     * @param $message
     * @param bool $showDate
     */
    protected function showMessage($message, $showDate = true)
    {
        $message = (true === $showDate) ? '['.date("Y-m-d H:i:s").'] '.$message : $message;
        $this->output->writeln($message);
    }
}
