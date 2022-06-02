<?php

namespace Micro\Plugin\Http\Business\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestBuilder implements RequestBuilderInterface
{
    private string $uri;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var array
     */
    private array $cookies;

    /**
     * @var array
     */
    private array $server;

    /**
     * @var array
     */
    private array $files;

    /**
     * @var string|resource|null
     */
    private mixed $content;

    /**
     * {@inheritDoc}
     */
    public function build(): Request
    {
        $this->init();

        return Request::create(
            $this->uri,
            $this->method,
            $this->parameters,
            $this->cookies,
            $this->files,
            $this->server,
            $this->content,
        );
    }

    /**
     * @return void
     */
    protected function init(): void
    {
        $this->server ??= $_SERVER;
        $this->cookies ??= $_COOKIE;
        $this->parameters ??= [];
        $this->content ??= null;
        $this->files ??= $_FILES;
        $this->method ??= 'GET';
    }

    /**
     * {@inheritDoc}
     */
    public function setUri(string $uri): RequestBuilderInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethod(string $method): RequestBuilderInterface
    {
        $this->method = $method;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setParameters(array $parameters): RequestBuilderInterface
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setCookies(array $cookies): RequestBuilderInterface
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setFiles(array $files): RequestBuilderInterface
    {
        $this->files = $files;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setServer(array $server): RequestBuilderInterface
    {
        $this->server = $server;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setContent(mixed $content): RequestBuilderInterface
    {
        $this->content = $content;

        return $this;
    }
}