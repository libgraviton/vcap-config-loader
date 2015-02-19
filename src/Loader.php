<?php
/**
 * load data from VCAP_SERVICES
 */

namespace Graviton\Vcap;

use Peekmo\JsonPath\JsonStore;

class Loader
{
    /**
     * @param string $input input string from VCAP_SERVICES env variable
     *
     * @return void
     */
    public function setInput($input)
    {
        $this->input = new JsonStore($input);
    }

    /**
     * @param string $path what to extract from service as JSON-Path
     *
     * @return mixed
     */
    public function get($path)
    {
        return array_pop($this->input->get($path));
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     * @param string $path subpath of service to extract
     *
     * @returns string
     */
    public function getByParam($type, $name, $path)
    {
        $jsonPath = sprintf("\$['%s'][?(@.name=='%s')].%s", $type, $name, $path);
        return $this->get($jsonPath);
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     *
     * @return string
     */
    public function getHost($type, $name)
    {
        return $this->getByParam($type, $name, 'credentials.host');
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     *
     * @return string
     */
    public function getPort($type, $name)
    {
        return $this->getByParam($type, $name, 'credentials.port');
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     *
     * @return string
     */
    public function getUsername($type, $name)
    {
        return $this->getByParam($type, $name, 'credentials.username');
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     *
     * @return string
     */
    public function getPassword($type, $name)
    {
        return $this->getByParam($type, $name, 'credentials.password');
    }

    /**
     * @param string $type type of service
     * @param string $name name of service
     *
     * @return string
     */
    public function getDatabase($type, $name)
    {
        return $this->getByParam($type, $name, 'credentials.database');
    }
}
