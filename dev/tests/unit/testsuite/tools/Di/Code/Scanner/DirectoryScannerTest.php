<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once realpath(dirname(__FILE__) . '/../../../../../../../') . '/tools/Di/Code/Scanner/DirectoryScanner.php';

class Magento_Tools_Di_Code_Scanner_DirectoryScannerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Magento\Tools\Di\Code\Scanner\DirectoryScanner
     */
    protected $_model;

    /**
     * @var string
     */
    protected $_testDir;

    protected function setUp()
    {
        $this->_model = new Magento\Tools\Di\Code\Scanner\DirectoryScanner();
        $this->_testDir = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../') . '/_files');
    }

    public function testScan()
    {
        $filePatterns = array(
            'php' => '/.*\.php$/',
            'etc' => '/\/app\/etc\/.*\.xml$/',
            'config' => '/\/etc\/(config([a-z0-9\.]*)?|adminhtml\/system)\.xml$/',
            'view' => '/\/view\/[a-z0-9A-Z\/\.]*\.xml$/',
            'design' => '/\/app\/design\/[a-z0-9A-Z\/\.]*\.xml$/',
        );

        $actual = $this->_model->scan($this->_testDir, $filePatterns);
        $expected = array(
            'php' => array(
                $this->_testDir . '/additional.php',
                $this->_testDir . '/app/bootstrap.php',
                $this->_testDir . '/app/code/Mage/SomeModule/Helper/Test.php',
                $this->_testDir . '/app/code/Mage/SomeModule/Model/Test.php',
            ),
            'config' => array(
                $this->_testDir . '/app/code/Mage/SomeModule/etc/adminhtml/system.xml',
                $this->_testDir . '/app/code/Mage/SomeModule/etc/config.xml'
            ),
            'view' => array(
                $this->_testDir . '/app/code/Mage/SomeModule/view/frontend/layout.xml',
            ),
            'design' => array(
                $this->_testDir . '/app/design/adminhtml/default/backend/layout.xml',
            ),
            'etc' => array(
                $this->_testDir . '/app/etc/additional.xml',
                $this->_testDir . '/app/etc/config.xml',
            ),
        );
        $this->assertEquals(sort($expected['php']), sort($actual['php']), 'Incorrect php files list');
        $this->assertEquals(sort($expected['config']), sort($actual['config']), 'Incorrect config files list');
        $this->assertEquals(sort($expected['view']), sort($actual['view']), 'Incorrect view files list');
        $this->assertEquals(sort($expected['design']), sort($actual['design']), 'Incorrect design files list');
        $this->assertEquals(sort($expected['etc']), sort($actual['etc']), 'Incorrect etc files list');
    }
}