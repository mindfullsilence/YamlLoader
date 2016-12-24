<?php

namespace Mindfullsilence;

use Symfony\Component\Yaml\Yaml as YamlParser;

class YamlLoader
{
		protected $config = array();
    protected $parameters = array();
    protected $global_parameters = array();

		public function getConfig($key = null) {
			$values = array();

			if($key === null) {
				$values = $this->config;
			} elseif(array_key_exists($key)) {
				$values = $this->config[$key];
			}

			return $values;
		}

    /**
     * Parse .yml file.
     *
     * @param string $file
     * @param string $resource (optional)
     *
     * @return self
     */
    public function addFile($file, $resource = null)
    {
        if (!file_exists($file) || !is_file($file)) {
            throw new \Exception('The configuration file ' . $file . ' does not exist.');
        } else {
            if ($resource === null) {
                $resource = $file;
            }

            if (!array_key_exists($resource, $this->parameters)) {
                $this->parameters[$resource] = new ParameterBag();
            }

            $content = YamlParser::parse(file_get_contents($file));

            if ($content !== null) {
                $content = $this->parseImports($content, $resource);

                $content = $this->parseParameters($content, $resource);

                $this->addConfig($content, $resource);
            }
        }

        return $this;
    }

    /**
     * Parse .yml files in a given directory.
     *
     * @param string $directory
     *
     * @return self
     */
    public function addDirectory($directory)
    {
        if (!file_exists($directory) || !is_dir($directory)) {
            throw new \Exception('The configuration directory does not exist.');
        } else {
            if (substr($directory, -1) != DIRECTORY_SEPARATOR) {
                $directory .= DIRECTORY_SEPARATOR;
            }

            foreach (glob($directory . '*.yml') as $file) {
                $this->addFile($file);
            }
        }

        return $this->getInstance();
    }

    /**
     * Adds global parameters for use by all resources.
     *
     * @param array $parameters
     *
     * @return self
     */
    public function addParameters(array $parameters)
    {
        $this->global_parameters = array_merge($this->global_parameters, $parameters);
        return $this;
    }

    /**
     * Adds to instance config for getting.
     *
     * @param array  $content
     * @param string $resource
     *
     * @return void
     */
    protected function addConfig(array $content, $resource)
    {
        foreach ($content as $key => $value) {
            $parameterBag = $this->parameters[$resource];

            $value = $parameterBag->unescapeValue($parameterBag->resolveValue($value));

						$this->config[$key] = $value;
        }

        return $this;
    }

    /**
     * Parses the imports section of a resource and includes them.
     *
     * @param array  $content
     * @param string $resource
     *
     * @return array
     */
    protected function parseImports(array $content, $resource)
    {
        if (isset($content['imports'])) {
            $chdir = dirname($resource);

            foreach ($content['imports'] as $import) {
								if(isset($content['id'])) {
									$resource = $content['id'];
								}

                $this->addFile($chdir . DIRECTORY_SEPARATOR . $import['resource'], $resource);
            }

            unset($content['imports']);
        }

        return $content;
    }

    protected function parseParameters($content, $resource)
    {
        $parameters = $this->global_parameters;
        if (isset($content['parameters'])) {
            $parameters = array_merge($content['parameters'], $parameters);

            unset($content['parameters']);
        }

        $this->parameters[$resource]->add($parameters);
        $this->parameters[$resource]->resolve();

        return $content;
    }

    public function __construct()
    {
      return $this;
    }
}
