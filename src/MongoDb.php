<?php

namespace Goletter\Mongodb;

use Goletter\Mongodb\Exception\MongoDBException;
use Goletter\Mongodb\Pool\PoolFactory;
use Hyperf\Context\Context;


/**
 * Class MongoDb
 * @package GiocoPlus\MongoDb
 */
class MongoDb
{
    /**
     * @var PoolFactory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $poolName = 'default';

    public function __construct(PoolFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * 返回满足filer的全部数据
     *
     * @param string $namespace
     * @param array $filter
     * @param array $options
     * @return array
     * @throws MongoDBException
     */
    public function fetchAll(string $namespace, array $filter = [], array $options = []): array
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->executeQueryAll($namespace, $filter, $options);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 返回满足filer的分页数据
     *
     * @param string $namespace
     * @param int $limit
     * @param int $currentPage
     * @param array $filter
     * @param array $options
     * @return array
     * @throws MongoDBException
     */
    public function fetchPagination(string $namespace, int $limit, int $currentPage, array $filter = [], array $options = []): array
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->execQueryPagination($namespace, $limit, $currentPage, $filter, $options);
        } catch (\Exception  $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 批量插入
     * @param $namespace
     * @param array $data
     * @param array $options
     * @return bool|string
     * @throws MongoDBException
     */
    public function insertAll($namespace, array $data, array $options = [])
    {
        if (count($data) == count($data, 1)) {
            throw new  MongoDBException('data is can only be a two-dimensional array');
        }
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->insertAll($namespace, $data, $options);
        } catch (MongoDBException $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 数据插入数据库
     *
     * @param $namespace
     * @param array $data
     * @param array $options
     * @return bool|mixed
     * @throws MongoDBException
     */
    public function insert($namespace, array $data = [], array $options = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->insert($namespace, $data, $options);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 数据 插入/更新 数据库
     *
     * @param $namespace
     * @param array $filter
     * @param array $data
     * @param array $options
     * @return bool|mixed
     * @throws MongoDBException
     */
    public function insertOrUpdate($namespace, array $filter, array $data = [], array $options = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->insertOrUpdate($namespace, $filter, $data, $options);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 更新数据满足$filter的行的信息成$newObject
     *
     * @param $namespace
     * @param array $filter
     * @param array $newObj
     * @return bool
     * @throws MongoDBException
     */
    public function updateRow($namespace, array $filter = [], array $newObj = [], $updateOpt = '$set' ): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->updateRow($namespace, $filter, $newObj, $updateOpt);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 只更新数据满足$filter的行的列信息中在$newObject中出现过的字段
     *
     * @param $namespace
     * @param array $filter
     * @param array $newObj
     * @return bool
     * @throws MongoDBException
     */
    public function updateColumn($namespace, array $filter = [], array $newObj = [], $updateOpt = '$set' ): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->updateColumn($namespace, $filter, $newObj, $updateOpt);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 替換满足$filter的行的列信息中在$newObject中出现过的字段
     *
     * @param $namespace
     * @param array $filter
     * @param array $newObj
     * @return bool
     * @throws MongoDBException
     */
    public function replaceOne($namespace, array $filter = [], array $newObj = [], array $opts = ['upsert' => false]): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            $opts += [
                'multi' => false,
            ];
            return $collection->replace($namespace, $filter, $newObj, $opts);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }
    /**
     * 删除满足条件的数据，默认只删除匹配条件的第一条记录，如果要删除多条$limit=true
     *
     * @param string $namespace
     * @param array $filter
     * @param bool $limit
     * @return bool
     * @throws MongoDBException
     */
    public function delete(string $namespace, array $filter = [], bool $limit = false): bool
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->delete($namespace, $filter, $limit);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 返回collection中满足条件的数量
     *
     * @param string $namespace
     * @param array $filter
     * @return bool
     * @throws MongoDBException
     */
    public function count(string $namespace, array $filter = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->count($namespace, $filter);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }


    /**
     * 聚合查询
     * @param string $namespace
     * @param array $filter
     * @return bool
     * @throws MongoDBException
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function command(string $namespace, array $filter = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->command($namespace, $filter);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * 執行command
     *
     * @param array $cmd
     * @return bool
     * @throws MongoDBException
     */
    public function excute(array $cmd = [])
    {
        try {
            /**
             * @var $collection MongoDBConnection
             */
            $collection = $this->getConnection();
            return $collection->excute($cmd);
        } catch (\Exception $e) {
            throw new MongoDBException($e->getFile() . $e->getLine() . $e->getMessage());
        }

    }

    /**
     * 選擇連結池
     *
     * @param string $poolName
     * @return $this
     */
    public function setPool(string $poolName): MongoDb {
        $this->poolName = $poolName;
        return $this;
    }

    private function getConnection()
    {
        $connection = null;
        $hasContextConnection = Context::has($this->getContextKey());
        if ($hasContextConnection) {
            $connection = Context::get($this->getContextKey());
        }
        if (!$connection instanceof MongoDbConnection) {
            $pool = $this->factory->getPool($this->poolName);
            $connection = $pool->get()->getConnection();
        }
        return $connection;
    }

    /**
     * The key to identify the connection object in coroutine context.
     */
    private function getContextKey(): string
    {
        return sprintf('mongodb.connection.%s', $this->poolName);
    }

}