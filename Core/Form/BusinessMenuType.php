<?php
/*
 * This file is part of the BusinessCarouselBundle and it is distributed
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

namespace AlphaLemon\Block\BusinessMenuBundle\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BusinessMenuType extends JsonBlockType
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $factoryRepository = $this->container->get('alpha_lemon_cms.factory_repository');
        $seoRepository = $factoryRepository->createRepository('Seo');//echo $this->container->get('request')->get('language');exit;
        $permalinks = $seoRepository->fromLanguageName('en');
        
        $links = array('#' => 'none');
        foreach ($permalinks as $permalink) {
            $url = $permalink->getPermalink();
            $links[$url] = $url;
        }
        
        $builder->add('title');
        $builder->add('subtitle');
        $builder->add('internal_link', 'choice', array('choices' => $links));
        $builder->add('external_link');
    }
}