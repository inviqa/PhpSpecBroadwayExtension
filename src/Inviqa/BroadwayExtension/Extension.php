<?php

namespace Inviqa\BroadwayExtension;

use Inviqa\BroadwayExtension\CodeGenerator\CommandHandlerClassGenerator;
use Inviqa\BroadwayExtension\CodeGenerator\CommandHandlerSpecificationGenerator;
use Inviqa\BroadwayExtension\CodeGenerator\ProjectorClassGenerator;
use Inviqa\BroadwayExtension\CodeGenerator\ProjectorSpecificationGenerator;
use Inviqa\BroadwayExtension\Console\Command\DescribeCommand;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->setShared('console.commands.describe', function () {
            return new DescribeCommand();
        });

        $container->setShared(
            'code_generator.generators.broadway_command_handler_class',
            function ($c) {
                return new CommandHandlerClassGenerator($c->get('console.io'), $c->get('code_generator.templates'));
            }
        );

        $container->setShared(
            'code_generator.generators.broadway_command_handler_specification',
            function ($c) {
                $generator = new CommandHandlerSpecificationGenerator($c->get('console.io'), $c->get('code_generator.templates'));
                $generator->setCommand($c->getParam('broadway.acts-on'));
                return $generator;
            }
        );

        $container->setShared(
            'code_generator.generators.broadway_projector_class',
            function ($c) {
                return new ProjectorClassGenerator($c->get('console.io'), $c->get('code_generator.templates'));
            }
        );

        $container->setShared(
            'code_generator.generators.broadway_projector_specification',
            function ($c) {
                $generator = new ProjectorSpecificationGenerator($c->get('console.io'), $c->get('code_generator.templates'));
                $generator->setEvent($c->getParam('broadway.acts-on'));
                return $generator;
            }
        );
    }
}
