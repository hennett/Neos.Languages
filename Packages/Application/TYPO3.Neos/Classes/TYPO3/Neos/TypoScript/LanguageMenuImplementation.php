<?php
namespace TYPO3\Neos\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Neos".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Service\ContentContextFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Neos\Domain\Service\ConfigurationContentDimensionPresetSource;
use TYPO3\TypoScript\Exception as TypoScriptException;
use TYPO3\TypoScript\TypoScriptObjects\Helpers\FluidView;

/**
 * A TypoScript Language Menu object
 */
class LanguageMenuImplementation extends \TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation {

    /**
     * @Flow\Inject
     * @var ConfigurationContentDimensionPresetSource
     */
    protected $configurationContentDimensionPresetSource;

    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contextFactory;

    /**
     * An internal cache for the built menu items array.
     * @var array
     */
    protected $items;

    /**
     * An internal cache for the current locale.
     * @var string
     */
    protected $currentLanguage;

    /**
     * @var NodeInterface
     */
    protected $currentNode;

    /**
     * This is a template method which can be overridden in subclasses to add new variables which should
     * be available inside the Fluid template. It is needed e.g. for Expose.
     *
     * @param FluidView $view
     * @return void
     * @throws TypoScriptException
     */
    protected function initializeView(FluidView $view) {
        $currentContext = $this->tsRuntime->getCurrentContext();
        if (!isset($currentContext['node'])) {
            throw new TypoScriptException('You must set a "node" in the TypoScript context.', 1391689525);
        }

        $this->currentNode = $currentContext['node'];

        $dimensions = $this->currentNode->getContext()->getDimensions();

        $currentLanguage = $this->configurationContentDimensionPresetSource->findPresetByDimensionValues('languages', $dimensions['languages']);
        $this->currentLanguage = $currentLanguage['identifier'];

        $view->assign('currentLanguage', $this->currentLanguage);
    }

    /**
     * Returns the menu items according to the defined settings.
     *
     * @return array
     */
    public function getItems() {
        if ($this->items === NULL) {
            $this->items = $this->buildItems();
        }
        return $this->items;
    }

    /**
     * Builds the array of menu items containing those items which match the
     * configuration set for this Menu object.
     *
     * @throws TypoScriptException
     * @return array An array of menu items and further information
     */
    protected function buildItems() {
        $items = array();

        $presets = $this->configurationContentDimensionPresetSource->getAllPresets();

        foreach ($presets['languages']['presets'] as $presetIdentifier => $presetConfiguration) {
            $localeContextProperties = $this->currentNode->getContext()->getProperties();
            unset($localeContextProperties['targetDimensions']);
            $localeContextProperties['dimensions']['languages'] = $presetConfiguration['values'];

            $localeContext = $this->contextFactory->create($localeContextProperties);
            $localizedNode = $localeContext->getNodeByIdentifier($this->currentNode->getIdentifier());

            if ($localizedNode instanceof NodeInterface) {
                $items[$presetIdentifier] = array(
                    'label' => $localizedNode->getProperty('title'),
                    'node' => $localizedNode,
                    'active' => $presetIdentifier === $this->currentLanguage
                );
            }
        }

        return $items;
    }

}
