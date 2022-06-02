<?php

namespace Micro\Plugin\Http\Console;

use Micro\Plugin\Http\Facade\HttpFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteMatchCommand extends Command
{
    const CMD_NAME = 'micro:router:match';
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
        $rb = $this->httpFacade->createRequestBuilder();
        $request = $rb
            ->setUri($input->getArgument(self::ARG_URI))
            ->setMethod($input->getArgument(self::ARG_METHOD) ?? 'GET')
            ->build()
        ;

        $result = $this->httpFacade->match($request);

        dump($result); exit;
    }
}