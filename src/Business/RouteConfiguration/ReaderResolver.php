<?php

namespace Micro\Plugin\Http\Business\RouteConfiguration;

class ReaderResolver implements ReaderResolverInterface
{

    /**
     * @param iterable<ReaderResolverConfigurationInterface> $readerResolverConfigurationCollection
     */
    public function __construct(private readonly iterable $readerResolverConfigurationCollection)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(string $format): RouteConfigurationReaderInterface
    {
        $format = $this->resolveFormat($format);

        foreach ($this->readerResolverConfigurationCollection as $configuration) {
            if(!$configuration->supports($format)) {
                continue;
            }

            return $configuration->resolve();
        }

        throw new \RuntimeException(sprintf('Route resource invalid: format "%s" is not supported', $format));
    }

    /**
     * @param string $format
     * @return string
     */
    protected function resolveFormat(string $format): string
    {
        $formatFile = realpath($format);

        if($formatFile && is_file($formatFile)) {
            $format = pathinfo($formatFile, PATHINFO_EXTENSION);
        }

        if(in_array(mb_strtolower($format), ['yaml', 'yml'])) {
            $format = 'yaml';
        }

        return $format;
    }
}