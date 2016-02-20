<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 18-02-16
 * Time: 16:03
 */

namespace AscensoDigital\PerfilBundle\Configuration;

/**
 * Expone configuracion completa o por filtro
 *
 * Class Configurator
 * @package AscensoDigital\ComponentBundle\Configuration
 */
class Configurator
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns the entire configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the configuration for the given filtro name.
     *
     * @param string $filtroName
     *
     * @return array The full filtro configuration
     *
     * @throws \InvalidArgumentException when the filtro isn't config
     */
    public function getFiltroConfiguration($filtroName)
    {
        if (!isset($this->config['filtros'][$filtroName])) {
            throw new \InvalidArgumentException(sprintf('Filtro "%s" is not managed.', $filtroName));
        }

        return $this->config['filtros'][$filtroName];
    }
}