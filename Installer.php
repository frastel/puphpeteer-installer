<?php

namespace Frastel\Puphpeteer;

use Composer\Script\Event;

/**
 * Copies the puppet modules installed by Composer
 * to the real puppet modules dir.
 *
 * Check "extra" section of composer.json for configuration.
 */
class Installer
{
    /**
     * @param Event $event
     *
     * @throws \InvalidArgumentException when extra configuration is not defined properly
     */
    static public function build(Event $event)
    {
        $io = $event->getIO();

        // everything needed for this installer
        // is stored in the "extra" config section
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (!array_key_exists('modules-dir', $extra) || !array_key_exists('modules', $extra)) {
            throw new \InvalidArgumentException('"extra" configuration needs properties "modules-dir" and "modules"');
        }

        // find out where we are and where we should install the puppet modules
        $moduleDir = $extra['modules-dir'];
        $vendorDir = realpath($event->getComposer()->getConfig()->get('vendor-dir'));
        $projectDir = realpath($vendorDir.'/..');

        // copy original puppet module dir from vendors to the puppet modules dir
        foreach ($extra['modules'] as $name => $target) {
            $modulePath = $moduleDir.'/' . $target;
            $io->write(sprintf('Installing Puppet module "%s" to "%s"', $name, $modulePath));

            $from = $vendorDir . '/' . $name;
            $to = $projectDir . '/' . $modulePath;

            // @TODO is there a better way without installing any other vendor lib?
            shell_exec("cp -r {$from} {$to}");
        }
    }
}
