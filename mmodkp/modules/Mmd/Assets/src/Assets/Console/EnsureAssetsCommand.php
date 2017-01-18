<?php

namespace Mmd\Assets\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class EnsureAssetsCommand
 *
 * @package Mmd\Assets\Console
 */
class EnsureAssetsCommand extends Command
{

    /**
     * @var string
     */
    protected $assetMapFile;

    /**
     * EnsureAssetsCommand constructor.
     *
     * @param string $assetMapFile
     * @param string $name
     */
    public function __construct($assetMapFile, $name = null)
    {
        $this->assetMapFile = $assetMapFile;
        parent::__construct($name ?: 'assets:ensure');
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Ensures that assets been assembled correctly');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->assetMapFile)) {
            $output->writeln(sprintf('<error>Asset map file %s does not exist</error>', $this->assetMapFile));

            return 1;
        }

        if (!is_readable($this->assetMapFile)) {
            $output->writeln(sprintf('<error>Asset map file %s is not readable</error>', $this->assetMapFile));

            return 1;
        }

        $content = file_get_contents($this->assetMapFile);

        if (empty($content)) {
            $output->writeln(sprintf('<error>Asset map file %s is empty</error>', $this->assetMapFile));

            return 1;
        }

        $mapArray = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $output->writeln(
                sprintf(
                    '<error>Asset map file %s has invalid json, error %s</error>',
                    $this->assetMapFile,
                    json_last_error_msg()
                )
            );

            return 1;
        }

        if (empty($mapArray)) {
            $output->writeln(sprintf('<error>Asset map file %s has an empty array</error>', $this->assetMapFile));

            return 1;
        }

        return 0;
    }
}
