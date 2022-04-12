<?php

// use Agregalel\Lturtle\Console\Generator;

if (file_exists(__DIR__.'/../../autoload.php')) {
    require __DIR__.'/../../autoload.php';
} else {
    require __DIR__.'/vendor/autoload.php';
}

require __DIR__.'/vendor/autoload.php';

// switch($argv[1]){
//     case 'generate':
//         $generator = new Generator();
//         echo $generator->generate($argv[2], $argv[3]);
//         break;
// }

$app = new Symfony\Component\Console\Application('Lturtle generate', '1.0.0');
$app->add(new Agregalel\Lturtle\Console\Commands\GenerateCommand);
$app->add(new Agregalel\Lturtle\Console\Commands\CreateCommand);

$app->run();