<?php

namespace Inviqa\PhpSpecBroadwayExtension\CodeGenerator;

use PhpSpec\CodeGenerator\Generator\SpecificationGenerator;
use PhpSpec\Locator\ResourceInterface;

class ProjectorSpecificationGenerator extends SpecificationGenerator
{
    private $eventName;

    public function setEvent($eventName)
    {
        $this->eventName = $eventName;
    }

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

        return 'specification' === $generation && $isHandler;
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
        return file_get_contents(__DIR__.'/templates/projector-specification.template');
    }

    /**
     * @param ResourceInterface $resource
     * @param string            $filepath
     *
     * @return string
     */
    protected function renderTemplate(ResourceInterface $resource, $filepath)
    {
        $values = array(
            '%filepath%'  => $filepath,
            '%name%'      => $resource->getSpecName(),
            '%namespace%' => $resource->getSpecNamespace(),
            '%subject%'   => $resource->getSrcClassname(),
            '%event%'   => $this->eventName ? $this->getEventExample() : '',
            '%eventImport%' => $this->eventName ? "\nuse $this->eventName;" : '',
        );

        $content = $this->getTemplateRenderer()->renderString($this->getTemplate(), $values);

        return $content;
    }

    private function getEventExample()
    {
        $parts = explode('\\', $this->eventName);
        $eventName = array_pop($parts);

         return '

    function it_projects_when_'.$this->toSnakeCase($eventName).'('.$eventName.' $'.lcfirst($eventName).')
    {
        $this->apply'.$eventName.'($'.lcfirst($eventName).');
    }';
    }

    private function toSnakeCase($input)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $input)), '_');
    }

}