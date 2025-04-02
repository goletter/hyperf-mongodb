<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Goletter\Mongodb;

use Goletter\Mongodb\Exception\InvalidMongodbConnectionException;

class MongodbConfiguration
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $database;

    /**
     * @var array
     */
    private $pool;

    /**
     * MongodbConfiguration constructor.
     */
    public function __construct(array $config)
    {
        $this->host = $config['host'] ?? [];
        $this->port = $config['port'] ?? 27017;
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->database = $config['database'] ?? '';
        $this->setOptions($config['options'] ?? []);
        $this->pool = $config['pool'] ?? [];
    }

    public function getHost(): array
    {
        return $this->host;
    }

    public function setHost(array $host): MongodbConfiguration
    {
        $this->host = $host;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): MongodbConfiguration
    {
        $this->port = $port;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): MongodbConfiguration
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): MongodbConfiguration
    {
        $this->password = $password;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): MongodbConfiguration
    {
        $config = [];
        if ($this->username) {
            $config['username'] = $this->username;
        }
        if ($this->password) {
            $config['password'] = $this->password;
        }

        $this->options = array_merge($options, $config);
        return $this;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): MongodbConfiguration
    {
        $this->database = $database;
        return $this;
    }

    public function getPool(): array
    {
        return $this->pool;
    }

    public function setPool(array $pool): MongodbConfiguration
    {
        $this->pool = $pool;
        return $this;
    }

    public function getDsn()
    {
        $hosts = [];
        if (! $this->getHost()) {
            throw new InvalidMongodbConnectionException('error mongodb config host');
        }
        foreach ($this->getHost() as $host) {
            $hosts[] = sprintf('%s:%d', $host, $this->getPort());
        }
        return 'mongodb://' . implode(',', $hosts);
    }
}
