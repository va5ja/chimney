<?php
namespace Plista\Chimney\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * @author Alexander Palamarchuk
 */
class Application extends BaseApplication {

	/**
	 * {@inheritDoc}
	 */
	public function run(InputInterface $input = null, OutputInterface $output = null)
	{
		if (null === $output) {
			$formatter = new OutputFormatter();
			$output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $formatter);
		}
		return parent::run($input, $output);
	}
}
