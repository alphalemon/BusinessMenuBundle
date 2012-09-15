<?php
/*
 * This file is part of the BusinessMenuBundle and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\Block\BusinessMenuBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPageAttributePeer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerBusinessMenu
 *
 * @author alphalemon
 */
class AlBlockManagerBusinessMenu extends AlBlockManagerJsonBlock
{
    private $container;

    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $dispatcher = $container->get('event_dispatcher');
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');
        parent::__construct($dispatcher, $factoryRepository, $validator);
    }

    public function getDefaultValue()
    {
        $defaultValue =
        '{
            "0" : {
                "title" : "Home",
                "subtitle" : "Welcome!",
                "internal_link" : "#",
                "external_link" : ""
            },
            "1" : {
                "title" : "News",
                "subtitle" : "Fresh",
                "internal_link" : "#",
                "external_link" : ""
            },
            "2" : {
                "title" : "Services",
                "subtitle" : "for you",
                "internal_link" : "#",
                "external_link" : ""
            },
            "3" : {
                "title" : "Products",
                "subtitle" : "The best",
                "internal_link" : "#",
                "external_link" : ""
            },
            "4" : {
                "title" : "Contacts",
                "subtitle" : "Our Address",
                "internal_link" : "#",
                "external_link" : ""
            }
        }';

        return array(
            'HtmlContent' => $defaultValue,
            'InternalJavascript' => '$(".business-menu a").doCufon();'
        );
    }

    public function getHtml()
    {
        if (null === $this->alBlock) return;

        $activePage = "";
        $pageTree = $this->container->get('alpha_lemon_cms.page_tree');
        $alLanguage = $pageTree->getAlLanguage();
        $alPage = $pageTree->getAlPage();
        if(null !== $alLanguage && null !== $alPage) {
            $seoRepository = $this->factoryRepository->createRepository('Seo');
            $seo = $seoRepository->fromPageAndLanguage($alLanguage->getId(), $alPage->getId());
            $activePage = $seo->getPermalink();
        }

        $i = 1;
        $elements = array();
        $items = $this->decodeJsonContent($this->alBlock, false);
        foreach ($items as $item) {
            $active = '';
            $link = $item->external_link;
            if ($link == "" || $item->internal_link != '#') {
                $link = $item->internal_link;
                if ($link == $activePage) $active = ' class="active"';
            }

            $elements[] = sprintf('<li id="nav%s"%s><a href="%s">%s<span>%s</span></a></li>', $i, $active, $link, $item->title, $item->subtitle);
        }

        return sprintf('<ul class="business-menu">%s</ul>', implode("\n", $elements));
    }
}