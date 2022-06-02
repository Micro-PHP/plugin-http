<?php

namespace Micro\Plugin\Http\Business\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestBuilderInterface
{
    /**
     * @param string $uri The URI
     *
     * @return $this
     */
    public function setUri(string $uri): self;

    /**
     * @param string $method The HTTP method
     * @return $this
     */
    public function setMethod(string $method): self;

    /**
     * @param array $parameters The query (GET) or request (POST) parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): self;

    /**
     * @param array $cookies The request cookies ($_COOKIE)
     *
     * @return $this
     */
    public function setCookies(array $cookies): self;

    /**
     * @param array $files The request files ($_FILES)
     *
     * @return $this
     */
    public function setFiles(array $files): self;

    /**
     * @param array $server The server parameters ($_SERVER)
     *
     * @return $this
     */
    public function setServer(array $server): self;

    /**
     * @param string|resource|null $content
     *
     * @return $this
     */
    public function setContent(mixed $content): self;

    /**
     * @return Request
     */
    public function build(): Request;
}