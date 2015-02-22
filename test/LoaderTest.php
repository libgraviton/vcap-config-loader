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
     * @return void
     */
    public function testSugarMethods()
    {
        $sut = new Loader;
        $sut->setInput(self::JSON_STRING_MARIADB);
        $this->assertEquals('127.0.0.1', $sut->getHost('mariadb-', 'example'));
        $this->assertEquals('3306', $sut->getPort('mariadb-', 'example'));
        $this->assertEquals('testuser', $sut->getUsername('mariadb-', 'example'));
        $this->assertEquals('testpass', $sut->getPassword('mariadb-', 'example'));
        $this->assertEquals('testbase', $sut->getDatabase('mariadb-', 'example'));
        $sut->setInput(self::JSON_STRING_MARIADB_2);
        $this->assertEquals('127.0.0.1', $sut->getHost('mariadb-9.8', 'example'));
        $this->assertEquals('3306', $sut->getPort('mariadb-9.8', 'example'));
        $this->assertEquals('testuser', $sut->getUsername('mariadb-9.8', 'example'));
        $this->assertEquals('testpass', $sut->getPassword('mariadb-9.8', 'example'));
        $this->assertEquals('testbase', $sut->getDatabase('mariadb-9.8', 'example'));
        $sut->setInput(self::JSON_STRING_MONGODB);
        $this->assertEquals('127.0.0.1', $sut->getHost('mongodb-2.2', 'example'));
        $this->assertEquals('4985', $sut->getPort('mongodb-2.2', 'example'));
        $this->assertEquals('db', $sut->getDatabase('mongodb-2.2', 'example'));
    }
}
