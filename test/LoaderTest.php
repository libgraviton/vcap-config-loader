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
    }
}
