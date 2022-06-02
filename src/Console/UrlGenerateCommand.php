<?php

namespace Micro\Plugin\Http\Console;

use Micro\Plugin\Http\Facade\HttpFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @todo: Provide arguments
 */
class UrlGenerateCommand extends Command
{
    const CMD_NAME = 'micro:router:generate';
    const ARG_PARAMETERS = 'parameters';
    const ARG_ROUTE_NAME = 'route';

    public function __construct(private readonly HttpFacadeInterface $httpFacade)
    {
        parent::__construct(self::CMD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->addArgument(self::ARG_ROUTE_NAME, InputArgument::REQUIRED, 'Route name');
        $this->addArgument(self::ARG_PARAMETERS, InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Route parameters array. For example: id=1 name=test');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArgument(self::ARG_PARAMETERS) ?? [];
        $result = $this->httpFacade->generateUrlByRouteName(
            $input->getArgument(self::ARG_ROUTE_NAME),
            $this->getArgumentsArray($input)
        );

        $output->writeln($result);

        return Command::SUCCESS;
    }

    protected function getArgumentsArray(InputInterface $input): array
    {
        $arguments = $input->getArgument(self::ARG_PARAMETERS);
        if(!$arguments) {
            return [];
        }
        $result = [];

        foreach ($arguments as $argument) {
            $tmp = explode('=', $argument);
            $result[$tmp[0]] = $tmp[1];
        }

        return $result;
    }
}