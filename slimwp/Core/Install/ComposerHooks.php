<?php
namespace Slimwp\Core\Install;
use Composer\Script\Event;

class ComposerHooks
{
    public static function preInstall(Event $event) {
        $io = $event->getIO();
        if ($io->askConfirmation("Are you sure you want to proceed? ", false)) {
            return true;
        }
        exit;
    }

    public static function postPackageInstall(Event $event) {
        $installedPackage = $event->getComposer()->getPackage();
        // any tasks to run after the package is installed
    }

    public static function configTool(Event $event) {
        echo "*** STARTING THE COFIGURATION TESTING AND SETUP TOOL" . PHP_EOL;
        // get access to the current Composer instance
        $composer = $event->getComposer();
        // get access to the current ComposerIOConsoleIO
        // stream for terminal input/output
        $io = $event->getIO();
        // paths
        $fn['settings'] = getcwd().'/slimwp/settings.php';
        if ( !file_exists($fn['settings']) ) {
          echo "*** Generating /slimwp/settings.php." . PHP_EOL;
          $str=file_get_contents(getcwd().'/slimwp/settings.dist.php');
          file_put_contents($fn['settings'], $str);
        }
        echo "+++ ALL IS WELL CONFIGURED." . PHP_EOL;
    }

}
