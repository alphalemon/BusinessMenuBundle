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

use AlphaLemon\Block\BusinessMenuBundle\Tests\TestCase;
use AlphaLemon\Block\BusinessMenuBundle\Core\Block\AlBlockManagerBusinessMenu;
use AlphaLemon\Block\BusinessMenuBundle\Core\Listener\RenderingEditorListener;


class TestBusinessMenuEditorListener extends RenderingEditorListener
{
    protected $configureParams = null;

    public function configure()
    {
        return parent::configure();
    }
}

/**
 * RenderingEditorListenerTest
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class RenderingEditorListenerTest extends TestCase
{
    public function testTheEditorHasBeenRendered()
    {
        $expectedResult = array(
            'blockClass' => '\AlphaLemon\Block\BusinessMenuBundle\Core\Block\AlBlockManagerBusinessMenu',
        );
        $listener = new TestBusinessMenuEditorListener();
        $this->assertEquals($expectedResult, $listener->configure());
    }
}