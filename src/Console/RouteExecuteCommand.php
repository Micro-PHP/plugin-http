<?php

namespace Micro\Plugin\Http\Console;

use Micro\Plugin\Http\Facade\HttpFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteExecuteCommand extends Command
{
    const CMD_NAME = 'micro:router:execute';
    const ARG_URI = 'uri';
    const ARG_METHOD = 'method';

    public function __construct(private readonly HttpFacadeInterface $httpFacade)
    {
        parent::__construct(self::CMD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->addArgument(self::ARG_URI, InputArgument::REQUIRED, 'Url to much. Example: /user/1');
        $this->addArgument(self::ARG_METHOD, InputArgument::OPTIONAL, 'HTTP Method. Default: GET');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        $rqb = $this->httpFacade->createRequestBuilder();

        $rqb
            ->setMethod($input->getArgument(self::ARG_METHOD) ?? 'GET')
            ->setUri($input->getArgument(self::ARG_URI))
        ;

        $this->httpFacade->handleRequest($rqb->build());

        return Command::SUCCESS;
    }
}