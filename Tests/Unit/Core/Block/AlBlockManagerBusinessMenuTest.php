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

namespace AlphaLemon\Block\BusinessMenuBundle\Tests\Unit\Core\Block;

use AlphaLemon\Block\BusinessMenuBundle\Core\Block\AlBlockManagerBusinessMenu;
use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;

/**
 * AlBlockManagerBusinessMenuTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlBlockManagerBusinessMenuTest extends AlBlockManagerContainerBase
{
    public function testDefaultValue()
    {
        $value =
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

        $expectedValue = array(
            'Content' => $value,
            'InternalJavascript' => '$(".business-menu a").doCufon();',
        );

        $this->initContainer();
        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }

    public function testAnEmptyStringIsReturnedWhenTheBlockHasNotBeenSet()
    {
        $this->initContainer();
        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $this->assertEquals('', $blockManager->getHtml());
    }

    public function testAnyPageIsActivatedWhenPageIsNull()
    {
        $block = $this->setUpBlock();
        $this->setUpSeoRepository('homepage', null, $this->setUpPage(0));

        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->eventsHandler, $factoryRepository, $this->pageTree));

        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $blockManager->set($block);
        $content = $blockManager->getHtml();
        
        $this->assertEquals($this->getExpectedResult(), $content);
    }

    public function testAnyPageIsActivatedWhenLanguageIsNull()
    {
        $block = $this->setUpBlock();
        $this->setUpSeoRepository('homepage', $this->setUpLanguage(0), null);

        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $factoryRepository->expects($this->once())
            ->method('createRepository')
            ->will($this->returnValue($this->blockRepository));
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->eventsHandler, $factoryRepository, $this->pageTree));

        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $blockManager->set($block);
        $content = $blockManager->getHtml();

        $this->assertEquals($this->getExpectedResult(), $content);
    }

    public function testHomepagePageIsActivated()
    {
        $block = $this->setUpBlock();
        $this->setUpSeoRepository('homepage', $this->setUpLanguage(), $this->setUpPage());

        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $factoryRepository->expects($this->exactly(2))
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->blockRepository, $this->seoRepository));
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->eventsHandler, $factoryRepository, $this->pageTree));

        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $blockManager->set($block);
        $content = $blockManager->getHtml();
        
        $expectedResult = $this->getExpectedResult();
        $expectedResult["RenderView"]["options"]["active_page"] = "homepage";
        
        $this->assertEquals($expectedResult, $content);
    }

    public function testDocumentPageIsActivated()
    {
        $block = $this->setUpBlock();
        $this->setUpSeoRepository('documentation', $this->setUpLanguage(), $this->setUpPage());

        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $factoryRepository->expects($this->exactly(2))
            ->method('createRepository')
            ->will($this->onConsecutiveCalls($this->blockRepository, $this->seoRepository));
        $this->container->expects($this->exactly(3))
                        ->method('get')
                        ->will($this->onConsecutiveCalls($this->eventsHandler, $factoryRepository, $this->pageTree));

        $blockManager = new AlBlockManagerBusinessMenu($this->container, $this->validator);
        $blockManager->set($block);
        $content = $blockManager->getHtml();
        
        $expectedResult = $this->getExpectedResult();
        $expectedResult["RenderView"]["options"]["active_page"] = "documentation";
        
        $this->assertEquals($expectedResult, $content);
    }
    
    private function getExpectedResult()
    {
        return array
        (
            "RenderView" => array
                (
                    "view" => "BusinessMenuBundle:Menu:menu.html.twig",
                    "options" => array
                        (
                            "items" => array
                                (
                                    array
                                        (
                                            "title" => "Home",
                                            "subtitle" => "Welcome!",
                                            "internal_link" => "homepage",
                                            "external_link" => "",
                                        ),

                                    array
                                        (
                                            "title" => "News",
                                            "subtitle" => "Fresh",
                                            "internal_link" => "documentation",
                                            "external_link" => "",
                                        ),

                                    array
                                        (
                                            "title" => "Services",
                                            "subtitle" => "for you",
                                            "internal_link" => "#",
                                            "external_link" => "",
                                        ),

                                    array
                                        (
                                            "title" => "Products",
                                            "subtitle" => "The best",
                                            "internal_link" => "#",
                                            "external_link" => "",
                                        ),

                                    array
                                        (
                                            "title" => "Contacts",
                                            "subtitle" => "Our Address",
                                            "internal_link" => "#",
                                            "external_link" => "",
                                        ),
                                ),
                            "active_page" => "",
                        ),
                ),
        );
    }

    private function setUpBlock()
    {
        $value =
        '{
            "0" : {
                "title" : "Home",
                "subtitle" : "Welcome!",
                "internal_link" : "homepage",
                "external_link" : ""
            },
            "1" : {
                "title" : "News",
                "subtitle" : "Fresh",
                "internal_link" : "documentation",
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

        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($value));

        return $block;
    }

    private function setUpSeoRepository($permalink, $language, $page)
    {
        $this->pageTree = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree')
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->pageTree->expects($this->once())
                       ->method('getAlLanguage')
                       ->will($this->returnValue($language));

        $this->pageTree->expects($this->once())
                       ->method('getAlPage')
                       ->will($this->returnValue($page));

        $this->seo= $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Model\AlSeo')
                          ->disableOriginalConstructor()
                          ->getMock();

        $times = (null !== $language && null !== $page) ? 1 : 0;
        $this->seo->expects($this->exactly($times))
                  ->method('getPermalink')
                  ->will($this->returnValue($permalink));

        $this->seoRepository = $this->getMockBuilder('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel\AlSeoRepositoryPropel')
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->seoRepository->expects($this->exactly($times))
                       ->method('fromPageAndLanguage')
                       ->will($this->returnValue($this->seo));
    }

    private function setUpLanguage($times = 1)
    {
        $language = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage');
        $language->expects($this->exactly($times))
                       ->method('getId')
                       ->will($this->returnValue(2));

        return $language;
    }

    private function setUpPage($times = 1)
    {
        $page= $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlPage');
        $page->expects($this->exactly($times))
                   ->method('getId')
                   ->will($this->returnValue(2));

        return $page;
    }
}