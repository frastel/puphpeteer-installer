# puphpeteer-installer

Installs versioned Puppet modules with PHP and Composer.

The default ![puppet module installer](http://docs.puppetlabs.com/puppet/2.7/reference/modules_installing.html) is too complicated for you or adding Puppet modules as git submodules feels strange?
Then this installer might be useful for you.

The required Puppet modules are installed via Composer in the `vendor` directory as usual,
however they are copied after Composer installation to the defined modules dir.
Afterwards all Puppet modules are located in one main directory and Puppet provisioning could be executed from there.

The resulting folder structure is then
```
puppet/
  manifests/
    <your manifest>.pp
  modules/
    <the installer copies the puppet modules here>
vendor/
  <user>/
    <project-name>/
      <the original downloaded puppet module>
```
The contents of the `modules` and `vendor` directory should be ignored from git.

This setup assumes that you have PHP and Composer already installed on your machine. So you can not use this setup
on a clean install. But anyway this was only an idea during development of ![PuPHPet](https://github.com/puphpet/puphpet/issues/56) and maybe it helps you in some cases.

## Usage in composer.json

Add `frastel/puphpeteer-installer` to your required packages.

```
    "require": {
        "frastel/puphpeteer-installer": "dev-master"
    },
```

Add the Puppet module installer to the `scripts` section:
```
    "scripts": {
        "post-install-cmd": "Frastel\\Puphpeteer\\Installer::build",
        "post-update-cmd": "Frastel\\Puphpeteer\\Installer::build"
    },
```

Define where the Puppet modules should be copied to and how they should be renamed during installation:
```
    "extra": {
        "puppet-modules-dir": "puppet/modules",
        "puppet-modules": {
            "jfryman/puppet-nginx":      "nginx",
            "puppetlabs/postgresql":     "postgresql",
            "frastel/puppet-phpmyadmin": "phpmyadmin",
            "frastel/puppet-symfony":    "symfony"
        }
    },
```
* *puppet-modules-dir*: Relative path dependent to your composer.json
* *puppet-modules*: A list of modules which are installed via Composer and should be copied to the `puppet-modules-dir`. The keys are the original project names and the values the expected puppet module names.
