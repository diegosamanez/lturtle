<?php

namespace Agregalel\Lturtle\Console\Commands;

use RuntimeException;
use Agregalel\Lturtle\Console\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate a new lturtle project')
            ->addArgument('type', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write(PHP_EOL.'<fg=red>
        | |   __|  |__     __      __|  |__ |  |
        | |  |__    __|  __\ |____|__    __||  |  ___
        | |     |  |  | |  | |___ \  |  |   |  | / _ \
        | |___  |  |  |_|  | |   \/  |  |   |  |/  __/
        |______ |__|\_____/| |       |__|   |__|\___|
        </>'.PHP_EOL.PHP_EOL);

        sleep(1);

        $type = $input->getArgument('type');
        $name = $input->getArgument('name');

        $generator = new Generator();
        $response = $generator->generate($type, $name);
            
        $commands = [
            "echo {$response}",
        ];
        if (($process = $this->runCommands($commands, $input, $output))->isSuccessful()) {

            $output->writeln(PHP_EOL."<comment>Application ready! Build something amazing.</comment>");
        }

        return $process->getExitCode();
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        $composerPath = getcwd().'/composer.phar';

        if (file_exists($composerPath)) {
            return '"'.PHP_BINARY.'" '.$composerPath;
        }

        return 'composer';
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  array  $env
     * @return \Symfony\Component\Process\Process
     */
    protected function runCommands($commands, InputInterface $input, OutputInterface $output, array $env = [])
    {
        if (! $output->isDecorated()) {
            $commands = array_map(function ($value) {
                if (substr($value, 0, 5) === 'chmod') {
                    return $value;
                }

                return $value.' --no-ansi';
            }, $commands);
        }

        if ($input->getOption('quiet')) {
            $commands = array_map(function ($value) {
                if (substr($value, 0, 5) === 'chmod') {
                    return $value;
                }

                return $value.' --quiet';
            }, $commands);
        }

        $process = Process::fromShellCommandline(implode(' && ', $commands), null, $env, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $output->writeln('Warning: '.$e->getMessage());
            }
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write('    '.$line);
        });

        return $process;
    }
}