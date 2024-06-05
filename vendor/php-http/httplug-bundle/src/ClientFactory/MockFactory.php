<?php

declare(strict_types=1);

namespace Http\HttplugBundle\ClientFactory;

use Http\Mock\Client;
use Psr\Http\Client\ClientInterface;

/**
 * @author Gary PEGEOT <garypegeot@gmail.com>
 */
final class MockFactory implements ClientFactory
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Set the client instance that this factory should return.
     *
     * Note that this can be any client, not only a mock client.
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function createClient(array $config = [])
    {
        if (!class_exists(Client::class)) {
            throw new \LogicException('To use the mock adapter you need to install the "php-http/mock-client" package.');
        }

        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}
