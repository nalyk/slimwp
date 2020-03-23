<?php
namespace Slimwp\Core\Install;
use Composer\Script\Event;
use Slimwp\Core\Classes\Crypto\Crypto;

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
        $fn['privkey']  = getcwd().'/private/crypto/private.key';
        $fn['pubkey']   = getcwd().'/private/crypto/public.key';
        $fn['phinx']    = getcwd().'/phinx.yml';
        $fn['settings'] = getcwd().'/slimwp/settings.php';
        // get settings interactively
        if ( !file_exists($fn['privkey']) ) {
          $ioresp['rsabit'] = $io->ask(">>> What rsa key bitsize do you want to use, should be >=1024 [2048]: ", "2048");
        }
        // do whats missing
        if ( !file_exists($fn['privkey']) ) {
          echo "*** Generating private key, this can take a while. If it goes on for too long, reduce keybit size." . PHP_EOL;
          exec("openssl genrsa -out ".$fn['privkey']." ".$ioresp['rsabit']);
          if ( file_exists($fn['pubkey']) ) { rename($fn['pubkey'], $fn['pubkey'].'.bak'); }
        }
        if ( !file_exists($fn['pubkey']) ) {
          echo "*** Generating public key." . PHP_EOL;
          exec("openssl rsa -in ".$fn['privkey']." -pubout -out ".$fn['pubkey']);
        }
        if ( !file_exists($fn['settings']) ) {
          echo "*** Generating /slimwp/settings.php." . PHP_EOL;
          $crypto = new Crypto;
          $str=file_get_contents(getcwd().'/slimwp/settings.dist.php');
          $str=str_replace("mail-encryption-key", $crypto->genkey_base64(), $str);
          $str=str_replace("reqparams-encryption-key", $crypto->genkey_base64(), $str);
          file_put_contents($fn['settings'], $str);
        }
        echo "+++ ALL IS WELL CONFIGURED." . PHP_EOL;
    }

}
