<?php
/**
 * test parsing of VCAP_SERVICES json
 */

namespace Graviton\Vcap;

use Graviton\Vcap\Loader;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    // @codingStandardsIgnoreStart
    const JSON_STRING_MARIADB = '{"mariadb-": [ {"name": "example", "credentials": {"host": "127.0.0.1", "port": "3306", "username": "testuser", "password": "testpass", "database": "testbase"} } ]}';
    const JSON_STRING_MARIADB_2 = '{"mariadb-9.8": [ {"name": "example", "credentials": {"host": "127.0.0.1", "port": "3306", "username": "testuser", "password": "testpass", "database": "testbase"} } ]}';
    const JSON_STRING_MONGODB   = '{"mongodb-2.2": [ {"name": "example", "credentials": {"host": "127.0.0.1", "port": "4985", "db": "db"}, "tags": ["nosql", "mongodb"] } ]}';
    // @codingStandardsIgnoreEmd

    /**
     * @dataProvider getProvider
     *
     * @param string $vcapString vcap config as string
     * @param string $type       type of service to get
     * @param string $name       name of service to get
     * @param string $path       what data to get
     * @param array  $expected   expected data as array
     *
     * @return void
     */
    public function testGet($vcapString, $path, $expected)
    {
        $sut = new Loader;
        $sut->setInput($vcapString);

        $data = $sut->get($path);
        $this->assertEquals($expected, $data);
    }

    /**
     * @return string<string,array>
     */
    public function getProvider()
    {
        return array(
            'by type' => array(self::JSON_STRING_MARIADB, "\$['mariadb-'][?(@['name']=='example')].name", 'example'),
            'version number in type' => array(self::JSON_STRING_MARIADB_2, "\$['mariadb-9.8'][?(@['name']=='example')].name", 'example'),
        );
    }

    /**
     * @dataProvider getByParamProvider
     */
    public function testGetByParam($vcapString, $type, $name, $data, $expected)
    {
        $sut = new Loader;
        $sut->setInput($vcapString);
        $this->assertEquals($expected, $sut->getByParam($type, $name, $data));
    }

    public function getByParamProvider()
    {
        return array(
            'get host' => array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'credentials.host', '127.0.0.1'),
            'version number in type' => array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'credentials.host', '127.0.0.1'),
        );
    }

    /**
     * @dataProvider sugarMethodsProvider
     *
     * @param string $json
     * @param string $type
     * @param string $name
     * @param string $method
     * @param string $value
     *
     * @return void
     */
    public function testSugarMethods($json, $type, $name, $method, $value)
    {
        $sut = new Loader;
        $sut->setInput($json);
        $this->assertEquals($value, $sut->$method($type, $name));
    }

    public function sugarMethodsProvider()
    {
        return array(
            array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'getHost', '127.0.0.1'),
            array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'getPort', '3306'),
            array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'getUsername', 'testuser'),
            array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'getPassword', 'testpass'),
            array(self::JSON_STRING_MARIADB, 'mariadb-', 'example', 'getDatabase', 'testbase'),
            array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'getHost', '127.0.0.1'),
            array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'getPort', '3306'),
            array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'getUsername', 'testuser'),
            array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'getPassword', 'testpass'),
            array(self::JSON_STRING_MARIADB_2, 'mariadb-9.8', 'example', 'getDatabase', 'testbase'),
            array(self::JSON_STRING_MONGODB, 'mongodb-2.2', 'example', 'getHost', '127.0.0.1'),
            array(self::JSON_STRING_MONGODB, 'mongodb-2.2', 'example', 'getPort', '4985'),
            array(self::JSON_STRING_MONGODB, 'mongodb-2.2', 'example', 'getDb', 'db'),
        );
    }
}
