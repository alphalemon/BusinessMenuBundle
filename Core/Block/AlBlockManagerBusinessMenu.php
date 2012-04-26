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

/**
 * AlBlockManagerBusinessMenu
 *
 * @author alphalemon
 */
class AlBlockManagerBusinessMenu extends AlBlockManager
{
    public function getDefaultValue()
    {
        $defaultContent = '<ul class="business-menu">
                            <li><a href="#">[Home, Welcome!]</a></li>
                            <li><a href="#">[News, Fresh]</a></li>
                            <li><a href="#">[Services, for you]</a></li>
                            <li><a href="#">[Products, The best]</a></li>
                            <li><a href="#">[Contacts, Our Address]</a></li>
                            </ul>';
        return array('HtmlContent' => $defaultContent,
            'InternalJavascript' => '$(".business-menu a").doCufon();');
    }
    
    public function getHtmlContent()
    {
        $content = $this->alBlock->getHtmlContent();
        
        // Finds the menu attributes
        preg_match_all('/<a[^>]*?href=["|\'](.*?)["|\']*?\>\[(.*)?, (.[^\]]*)?\]*/', $content, $matches); 
        if(isset($matches[3]) && !empty($matches[3]))
        {
            // Assigns the attributes
            $links = $matches[1];
            $menuValues = array_combine($matches[2], $matches[3]);

            // Finds the active page
            $c = new \Criteria();
            $c->add(AlPageAttributePeer::TO_DELETE, 0);
            $c->add(AlPageAttributePeer::LANGUAGE_ID, $this->container->get('al_page_tree')->getAlLanguage()->getId());
            $pageAttributes = $this->container->get('al_page_tree')->getAlPage()->getAlPageAttributes($c);
            $permalink = $pageAttributes[0]->getPermalink();
            
            // Builds the menu items
            $i = 1;
            $menuItems = array();  
            foreach($menuValues as $title => $subtitle)
            {
                $link = array_shift($links);
                $class = ($link == $permalink) ? ' class="active"' : '';
                $menuItems[] = sprintf('<li id="nav%s"%s><a href="%s">%s<span>%s</span></a></li>', $i, $class, $link, $title, $subtitle);
                $i++;
            }

            // Returns the menu
            return sprintf('<ul class="business-menu">%s</ul>', implode("\n", $menuItems));
        }
        
        return $content;
    }
    
    public function getHtmlContentCMSMode()
    {
        return $this->getHtmlContent() . '<script type="text/javascript">$(document).ready(function(){ $(\'.business-menu a\').doCufon(); });</script>';
    }
}