<?php

namespace Inviqa\PhpSpecBroadwayExtension\CodeGenerator;

use PhpSpec\CodeGenerator\Generator\SpecificationGenerator;
use PhpSpec\Locator\ResourceInterface;

class CommandHandlerSpecificationGenerator extends SpecificationGenerator
{
    private $commandName;

    public function setCommand($commandName)
    {
        $this->commandName = $commandName;
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
        $needle = 'CommandHandler';
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
        return file_get_contents(__DIR__.'/templates/command-handler-specification.template');
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
            '%command%'   => $this->commandName ? $this->getCommandExample() : '',
            '%commandImport%' => $this->commandName ? "\nuse $this->commandName;" : '',
        );

        $content = $this->getTemplateRenderer()->renderString($this->getTemplate(), $values);

        return $content;
    }

    private function getCommandExample()
    {
        $parts = explode('\\', $this->commandName);
        $commandName = array_pop($parts);


         return '

    function it_supports_'.$this->toSnakeCase($commandName).'('.$commandName.' $'.lcfirst($commandName).')
    {
        $this->handle'.$commandName.'($'.lcfirst($commandName).');
    }';
    }

    private function toSnakeCase($input)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $input)), '_');
    }

}