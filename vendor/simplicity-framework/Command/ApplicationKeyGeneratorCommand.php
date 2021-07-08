<?php

namespace Sim\Command;

use Symfony\{Component\Console\Command\Command,
    Component\Console\Input\InputInterface,
    Component\Console\Output\OutputInterface,
    Component\Console\Style\SymfonyStyle};

class ApplicationKeyGeneratorCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('key:generate')
            // the short description shown while running "php bin/console list"
            ->setDescription('Generate a secure key for application.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Generate a secure key for entire application encoding.');
    }

    /**
     * {@inheritdoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Sim\Exceptions\ConfigManager\ConfigNotRegisteredException
     * @throws \Sim\Interfaces\IFileNotExistsException
     * @throws \Sim\Interfaces\IInvalidVariableNameException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generate Application Key:');

        $generatedKey = $this->generateKey();
        $assuredGeneratedKey = $this->generateKey();

        // put generated key in .env file
        $envPath = trim(str_replace('\\', '/', config()->get('main.env_path')), '/') . '/.env';
        if (!\file_exists($envPath)) {
            $io->error(
                'There is no .env file to append generated keys.'
            );
            $io->info(
                'Here are your keys:' .
                ' APP_MAIN_KEY: ' . $generatedKey .
                ' APP_ASSURED_KEY: ' . $assuredGeneratedKey
            );
        }

        $envContent = file_get_contents($envPath);
        $envContent = trim($envContent);
        $pattern = '/APP_MAIN_KEY\s*\=\s*[^\n\r]*/';
        $pattern2 = '/APP_ASSURED_KEY\s*\=\s*[^\n\r]*/';
        if ((bool)preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, 'APP_MAIN_KEY=' . $generatedKey, $envContent);
        } else {
            $envContent = 'APP_MAIN_KEY=' . $generatedKey . "\t\n" . $envContent;
        }
        if ((bool)preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern2, 'APP_ASSURED_KEY=' . $assuredGeneratedKey, $envContent);
        } else {
            $envContent = 'APP_ASSURED_KEY=' . $assuredGeneratedKey . "\t\n" . $envContent;
        }
        file_put_contents($envPath, $envContent);
        //

        $io->success(
            'Application keys has been generated successfully.'
        );

        return Command::SUCCESS;
    }

    /**
     * @see https://stackoverflow.com/a/31357966/12154893
     * @return string
     */
    private function generateKey(): string
    {
        $strong = true;
        $bytes = openssl_random_pseudo_bytes(32, $strong);
        $key = base64_encode($bytes);
        return $key;
    }
}