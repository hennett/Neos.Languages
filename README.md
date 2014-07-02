Neos.Languages
==============

A simple "plugin" with detailed instructions on how to activate and use languages in Neos

Usage
=====
In order to have the language menu switcher available in our site, we have to create in `Root.ts2`
> languageMenu = TYPO3.Neos:LanguageMenu

and add it in our template file
> {parts.languageMenu -> f:format.raw()}
	
	
Objects.yaml
------------
> TYPO3\Neos\Routing\FrontendNodeRoutePartHandlerInterface:
>  className: TYPO3\Neos\Routing\LocalizedFrontendNodeRoutePartHandler
  
Settings.yaml
-------------
> TYPO3:
>   TYPO3CR:
>     contentDimensions:
>       languages:
>         default: en_GB
>   Neos:
>     contentDimensions:
>       dimensions:
>         'languages':
>           defaultPreset: 'en_GB'
>           label: 'Languages'
>           icon: 'icon-language'
>           presets:
>             'de_DE':
>               label: 'Deutsch'
>               values: ['de_DE', 'en_GB']
>               uriSegment: 'de'
>             'en_GB':
>               label: 'English'
>               values: ['en_GB']
>               uriSegment: 'en'
>             'el_GR':
>               label: 'Greek'
>               values: ['el_GR', 'en_GB']
>               uriSegment: 'el'
			  
CLI
---
> ./flow flow:cache:flush -f
> ./flow site:import --package-key Key.YourSite
> ~mysqldump nodedimensions~
> ./flow node:migrate --version 20140516221523
> ~import mysqldump~
