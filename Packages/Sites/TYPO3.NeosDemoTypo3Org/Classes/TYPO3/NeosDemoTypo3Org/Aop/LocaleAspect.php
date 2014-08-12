<?php
namespace TYPO3\NeosDemoTypo3Org\Aop;

/* *
 * This script belongs to the MyCompany.MyPackage.
 * */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Locale;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * The FrontendI18nLocaleSwitchingAspect
 *
 * @Flow\Aspect
 */
class LocaleAspect {

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\I18n\Service
     */
    protected $localizationService;


    /**
     * Switches locale of i18n service depending on the locale in the Neos content context
     *
     * @Flow\Before("method(TYPO3\Neos\Controller\Frontend\NodeController->showAction())")
     * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
     * @return NodeInterface
     */
    public function switchLocale(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
        /** @var NodeInterface $node */
        $node = $joinPoint->getMethodArgument('node');
        $contextProperties = $node->getContext()->getProperties();
        if (isset($contextProperties['dimensions']['locales']) && is_array($contextProperties['dimensions']['locales'])) {
            $locales = $contextProperties['dimensions']['locales'];
            if ((!empty($locales))) {
                $this->localizationService->getConfiguration()->setFallbackRule(array('strict' => FALSE, 'order' => $locales));
                $this->localizationService->getConfiguration()->setCurrentLocale(new Locale($locales[0]));
            }
        }
    }
}
