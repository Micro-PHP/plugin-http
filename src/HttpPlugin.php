<?php

namespace Micro\Plugin\Http;

use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactory;
use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactoryInterface;
use Micro\Component\DependencyInjection\Container;
use Micro\Component\EventEmitter\ListenerProviderInterface;
use Micro\Framework\Kernel\Plugin\AbstractPlugin;
use Micro\Kernel\App\Listener\ApplicationListenerProviderPluginInterface;
use Micro\Plugin\Configuration\Helper\Facade\ConfigurationHelperFacadeInterface;
use Micro\Plugin\Console\CommandProviderInterface;
use Micro\Plugin\Http\Business\Context\RequestContextFactory;
use Micro\Plugin\Http\Business\Context\RequestContextFactoryInterface;
use Micro\Plugin\Http\Business\Handler\RequestHandlerFactory;
use Micro\Plugin\Http\Business\Handler\RequestHandlerFactoryInterface;
use Micro\Plugin\Http\Business\Logger\HttpLoggerFactory;
use Micro\Plugin\Http\Business\Logger\HttpLoggerFactoryInterface;
use Micro\Plugin\Http\Business\Matcher\UrlMatcherFactory;
use Micro\Plugin\Http\Business\Matcher\UrlMatcherFactoryInterface;
use Micro\Plugin\Http\Business\Request\RequestBuilderFactory;
use Micro\Plugin\Http\Business\Request\RequestBuilderFactoryInterface;
use Micro\Plugin\Http\Business\Route\RouteCollectionFactory;
use Micro\Plugin\Http\Business\Route\RouteCollectionFactoryInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolver;
use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolverConfiguration;
use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolverConfigurationInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\ReaderResolverInterface;
use Micro\Plugin\Http\Business\RouteConfiguration\Yaml\YamlRouteConfigurationReaderFactory;
use Micro\Plugin\Http\Business\RouteHandler\Extractor\RouteHandlerExtractorFactory;
use Micro\Plugin\Http\Business\RouteHandler\Extractor\RouteHandlerExtractorFactoryInterface;
use Micro\Plugin\Http\Business\RouteProvider\RouteProviderFactory;
use Micro\Plugin\Http\Business\RouteProvider\RouteProviderFactoryInterface;
use Micro\Plugin\Http\Business\UrlGenerator\UrlGeneratorFactory;
use Micro\Plugin\Http\Business\UrlGenerator\UrlGeneratorFactoryInterface;
use Micro\Plugin\Http\Configuration\HttpPluginConfigurationInterface;
use Micro\Plugin\Http\Console\RouteExecuteCommand;
use Micro\Plugin\Http\Console\RouteMatchCommand;
use Micro\Plugin\Http\Console\UrlGenerateCommand;
use Micro\Plugin\Http\Facade\HttpFacade;
use Micro\Plugin\Http\Facade\HttpFacadeInterface;
use Micro\Plugin\Http\Handler\HandlerAbstractFactory;
use Micro\Plugin\Http\Handler\HandlerAbstractFactoryInterface;
use Micro\Plugin\Http\Listener\ApplicationStartListener;
use Micro\Plugin\Http\Listener\ListenerProvider;
use Micro\Plugin\Logger\LoggerFacadeInterface;
use Psr\Container\ContainerInterface;

/**
 * @method HttpPluginConfigurationInterface configuration()
 *
 * ApplicationListenerProviderPluginInterface
 */
class HttpPlugin extends AbstractPlugin implements CommandProviderInterface, ApplicationListenerProviderPluginInterface
{

    protected ?ContainerInterface $container;

    /**
     * {@inheritDoc}
     */
    public function provideDependencies(Container $container): void
    {
        $this->container = $container;

        $container->register(HttpFacadeInterface::class, function (
            Container $container,
            LoggerFacadeInterface $loggerFacade,
            ConfigurationHelperFacadeInterface $configurationHelperFacade
        ) {
            return $this->createFacade($container, $loggerFacade, $configurationHelperFacade);
        });
    }

    /**
     * @param Container $container
     * @param LoggerFacadeInterface $loggerFacade
     * @param ConfigurationHelperFacadeInterface $configurationHelperFacade
     *
     * @return HttpFacadeInterface
     */
    protected function createFacade(Container $container, LoggerFacadeInterface $loggerFacade, ConfigurationHelperFacadeInterface $configurationHelperFacade): HttpFacadeInterface
    {
        $autowireHelperFactory = $this->createAutowireHelperFactory($container);
        $routeHandlerAbstractFactory = $this->createRouteHandlerAbstractFactory($autowireHelperFactory);
        $routeHandlerExtractorFactory = $this->createRouteHandlerExtractorFactory($routeHandlerAbstractFactory);
        $httpLoggerFactory = $this->createHttpLoggerFactory($loggerFacade);
        $routeProviderFactory = $this->createRouteProviderFactory($configurationHelperFacade);
        $routeCollectionFactory = $this->createRouteCollectionFactory($routeProviderFactory);
        $requestContextGeneratorFactory = $this->createRequestContextFactory();
        $urlMatcherFactory = $this->createUrlMatcherFactory($routeCollectionFactory, $loggerFacade);
        $requestHandlerFactory = $this->createRequestHandlerFactory(
            $urlMatcherFactory,
            $httpLoggerFactory,
            $autowireHelperFactory,
            $routeHandlerExtractorFactory
        );
        $requestBuilderFactory = $this->createRequestBuilderFactory();
        $urlGeneratorFactory = $this->createUrlGeneratorFactory($routeCollectionFactory, $requestContextGeneratorFactory);

        return new HttpFacade(
            $urlMatcherFactory,
            $requestHandlerFactory,
            $requestBuilderFactory,
            $urlGeneratorFactory,
            $container
        );
    }

    /**
     * @param RouteCollectionFactoryInterface $routeCollectionFactory
     * @param RequestContextFactoryInterface $requestContextFactory
     * @return UrlGeneratorFactoryInterface
     */
    protected function createUrlGeneratorFactory(RouteCollectionFactoryInterface $routeCollectionFactory, RequestContextFactoryInterface $requestContextFactory): UrlGeneratorFactoryInterface
    {
        return new UrlGeneratorFactory(
            $routeCollectionFactory,
            $requestContextFactory
        );
    }

    /**
     * @return RequestContextFactoryInterface
     */
    protected function createRequestContextFactory(): RequestContextFactoryInterface
    {
        return new RequestContextFactory();
    }

    /**
     * @param RouteCollectionFactoryInterface $routeCollectionFactory
     * @param LoggerFacadeInterface $loggerFacade
     *
     * @return UrlMatcherFactoryInterface
     */
    protected function createUrlMatcherFactory(
        RouteCollectionFactoryInterface $routeCollectionFactory,
        LoggerFacadeInterface $loggerFacade
    ): UrlMatcherFactoryInterface
    {
        return new UrlMatcherFactory(
            $routeCollectionFactory,
            $this->configuration(),
            $loggerFacade
        );
    }

    /**
     * @param RouteProviderFactoryInterface $routeProviderFactory
     *
     * @return RouteCollectionFactoryInterface
     */
    protected function createRouteCollectionFactory(RouteProviderFactoryInterface $routeProviderFactory): RouteCollectionFactoryInterface
    {
        return new RouteCollectionFactory($routeProviderFactory);
    }

    /**
     * @param ConfigurationHelperFacadeInterface $configurationHelperFacade
     *
     * @return RouteProviderFactoryInterface
     */
    protected function createRouteProviderFactory(ConfigurationHelperFacadeInterface $configurationHelperFacade): RouteProviderFactoryInterface
    {
        $configuration = $this->configuration();

        return new RouteProviderFactory(
            $this->createReaderResolver($configurationHelperFacade),
            $configurationHelperFacade,
            $configuration->getConfigurationDestination(),
        );
    }

    /**
     * @param ConfigurationHelperFacadeInterface $configurationHelperFacade
     *
     * @return ReaderResolverInterface
     */
    protected function createReaderResolver(ConfigurationHelperFacadeInterface $configurationHelperFacade): ReaderResolverInterface
    {
        return new ReaderResolver(
            $this->createReaderResolverCollection($configurationHelperFacade)
        );
    }

    /**
     * @param ConfigurationHelperFacadeInterface $configurationHelperFacade
     *
     * @return iterable<ReaderResolverConfigurationInterface>
     */
    protected function createReaderResolverCollection(
        ConfigurationHelperFacadeInterface $configurationHelperFacade
    ): iterable
    {
        return [
            new ReaderResolverConfiguration(
                new YamlRouteConfigurationReaderFactory($configurationHelperFacade),
                'yaml'
            ),

        ];
    }

    /**
     * @return RequestBuilderFactoryInterface
     */
    protected function createRequestBuilderFactory(): RequestBuilderFactoryInterface
    {
        return new RequestBuilderFactory();
    }

    /**
     * @param LoggerFacadeInterface $loggerFacade
     * @return HttpLoggerFactoryInterface
     */
    protected function createHttpLoggerFactory(LoggerFacadeInterface $loggerFacade): HttpLoggerFactoryInterface
    {
        return new HttpLoggerFactory(
            $loggerFacade,
            $this->configuration()
        );
    }

    /**
     * @param UrlMatcherFactoryInterface $urlMatcherFactory
     * @param HttpLoggerFactoryInterface $httpLoggerFactory
     * @param AutowireHelperFactoryInterface $autowireHelperFactory
     * @param RouteHandlerExtractorFactory $routeHandlerExtractorFactory
     *
     * @return RequestHandlerFactoryInterface
     */
    protected function createRequestHandlerFactory(
        UrlMatcherFactoryInterface $urlMatcherFactory,
        HttpLoggerFactoryInterface $httpLoggerFactory,
        AutowireHelperFactoryInterface $autowireHelperFactory,
        RouteHandlerExtractorFactory $routeHandlerExtractorFactory
    ): RequestHandlerFactoryInterface
    {
        return new RequestHandlerFactory(
            $urlMatcherFactory,
            $autowireHelperFactory,
            $routeHandlerExtractorFactory,
            $httpLoggerFactory
        );
    }

    protected function createRouteHandlerExtractorFactory(HandlerAbstractFactoryInterface $handlerAbstractFactory): RouteHandlerExtractorFactoryInterface
    {
        return new RouteHandlerExtractorFactory($handlerAbstractFactory);
    }

    /**
     * @param AutowireHelperFactoryInterface $autowireHelperFactory
     * @return HandlerAbstractFactoryInterface
     */
    protected function createRouteHandlerAbstractFactory(AutowireHelperFactoryInterface $autowireHelperFactory): HandlerAbstractFactoryInterface
    {
        return new HandlerAbstractFactory($autowireHelperFactory);
    }

    /**
     * @param ContainerInterface $container
     * @return AutowireHelperFactoryInterface
     */
    protected function createAutowireHelperFactory(ContainerInterface $container): AutowireHelperFactoryInterface
    {
        return new AutowireHelperFactory($container);
    }

    /**
     * @param Container $container
     */
    public function provideConsoleCommands(Container $container): array
    {
        $facade = $container->get(HttpFacadeInterface::class);

        return [
            new RouteMatchCommand($facade),
            new UrlGenerateCommand($facade),
            new RouteExecuteCommand($facade)
        ];
    }

    public function getEventListenerProvider(): ListenerProviderInterface
    {
        return new ListenerProvider(
            new ApplicationStartListener($this->container->get(HttpFacadeInterface::class))
        );
    }
}