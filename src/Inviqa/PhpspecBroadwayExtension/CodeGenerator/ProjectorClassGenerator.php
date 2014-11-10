<?php

namespace Inviqa\PhpSpecBroadwayExtension\CodeGenerator;

use PhpSpec\CodeGenerator\Generator\ClassGenerator;
use PhpSpec\Locator\ResourceInterface;

class ProjectorClassGenerator extends ClassGenerator
{
    /**
     * @param ResourceInterface $resource
     * @param string            $generation
     * @param array             $data
     *
     * @return boolean
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        $needle = 'Projector';
        $isHandler = substr($resource->getName(), -strlen($needle)) === $needle;

        return 'class' === $generation && $isHandler;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__DIR__.'/templates/projector-class.template');
    }
}