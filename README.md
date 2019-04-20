# LoginRequire
Require Customer Login before access web

# Installation

```
composer.phar require customerlogin/login-require:dev-master
```

After the installment of the module source code, the module has to be enabled by the *Magento® 2* CLI.

```
bin/magento module:enable CustomerLogin_LoginRequire
```

## System Upgrade
After enabling the module, the Magento® 2 system must be upgraded.

If the system mode is set to production, run the compile command first. This is not necessary for the developer mode.

```
bin/magento setup:di:compile
```

To upgrade the system, the *upgrade* command must be run.

```
bin/magento setup:upgrade
```

## Clear Cache

```
bin/magento cache:clean
```

After installing and enabling Module, At Admin you must navigate to Stores > Configuration > Customer > Customer Configuration, where you are able to configure the availability of the module for website
