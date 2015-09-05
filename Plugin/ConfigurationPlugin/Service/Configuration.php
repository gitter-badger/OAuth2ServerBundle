<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ConfigurationPlugin\Service;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->options)) {
            unset($this->options[$name]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationKeys()
    {
        return array_keys($this->options);
    }
}
