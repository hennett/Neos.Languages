Neos.Languages
==============

A simple "plugin" with detailed instructions on how to activate and use languages in Neos

Installation-Usage
==================
Extract the contents in your project folder. Overwrite potential conflicts. The html of the language menu is based on [Bootstrap 3](http://getbootstrap.com/). There is no need to include the typoscript file of the plugin in your project's `Root.ts2`.


In order to have the language menu switcher available in our site, we have to create in `Root.ts2`

`languageMenu = TYPO3.Neos:LanguageMenu`

and add it in our template file

`{languageMenu -> f:format.raw()}`


Add the following files (`Objects.yaml`, `Settings.yaml`) in your project's site and run the following CLI commands.
	
	
Objects.yaml
------------
```yaml
TYPO3\Neos\Routing\FrontendNodeRoutePartHandlerInterface:
  className: TYPO3\Neos\Routing\LocalizedFrontendNodeRoutePartHandler
```
  
Settings.yaml
-------------
```yaml
TYPO3:
  TYPO3CR:
    contentDimensions:
      languages:
        default: en_GB
  Neos:
    contentDimensions:
      dimensions:
        'languages':
          defaultPreset: 'en_GB'
          label: 'Languages'
          icon: 'icon-language'
          presets:
            'de_DE':
              label: 'Deutsch'
              values: ['de_DE', 'en_GB']
              uriSegment: 'de'
            'en_GB':
              label: 'English'
              values: ['en_GB']
              uriSegment: 'en'
            'el_GR':
              label: 'Greek'
              values: ['el_GR', 'en_GB']
              uriSegment: 'el'
```			  
CLI
---
`./flow flow:cache:flush -f`

`./flow site:import --package-key TYPO3.NeosDemoTypo3Org` *(optional step)*

`~mysqldump typo3_typo3cr_domain_model_nodedimension~`

`./flow node:migrate --version 20140516221523`

`~import mysqldump~`

Note
====
You have to change the `package` property of `line 6` and `line 18` in `LnaguageMenu.html` to your package name.

Same goes for the `LocaleAspect`.php; change its path and namespace.
