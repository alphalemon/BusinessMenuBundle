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

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * AlBlockManagerBusinessMenu
 *
 * @author alphalemon
 */
class AlBlockManagerBusinessMenu extends AlBlockManagerJsonBlockContainer
{
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
            'Content' => $defaultValue,
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
            if (null !== $seo) {
                $activePage = $seo->getPermalink();
            }
        }
        
        $items = $this->decodeJsonContent($this->alBlock);
        return array(
            "RenderView" => array(
                "view" => "BusinessMenuBundle:Menu:menu.html.twig",
                "options" => array(
                    "items" => $items,
                    "active_page" => $activePage,
                )
            )
        );
    }

    protected function getEditorWidth()
    {
        return 600;
    }
}