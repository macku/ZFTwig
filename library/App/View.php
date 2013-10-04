<?php
/**
 * This file is part of the App_ZFTwig package
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @copyright  Copyright (c) 2010-2011 Benjamin Dulau <benjamin.dulau@gmail.com>
 * @license    New BSD License
 */

/**
 * Concrete class for handling view scripts.
 * Support multiple template engines. If none, render phtml
 *
 * @category   App
 * @package    App_View
 * @author     Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class App_View extends Zend_View_Abstract
{

    /**
     * @var array Array Template engines list, key-value pairs
     */
    protected $templateEngines = array();

    /**
     * @var string Key in $templateEngines of the default engine
     */
    protected $defaultTemplateEngine = null;

    /**
     * @var string Key in $templateEngines of current template Engine
     */
    protected $templateEngine = null;


    /**
     * @param string $key Set the key in $templateEngines of the
     *                    default Engine.
     * @return App_View
     */
    public function setDefaultTemplateEngine($key)
    {
        $this->defaultTemplateEngine = $key;
        return $this;
    }

    /**
     * @return App_View_Engine_Interface
     */
    public function getDefaultTemplateEngine()
    {
        return $this->defaultTemplateEngine;
    }

    /**
     * @param string $key Set the key in $templateEngines of the
     *                    active Engine.
     * @return App_View
     */
    public function setTemplateEngine($key)
    {
        $this->templateEngine = $key;
        return $this;
    }

    /**
     * Checks if the template engine with key $key exists
     * in $templateEngines.
     *
     * @param string $key
     * @return bool
     */
    public function hasTemplateEngine($key)
    {
        return array_key_exists($key, $this->templateEngines);
    }

    /**
     * Returns the current active template engine
     * or the default one.
     *
     * @return App_View_Engine_Interface
     * @throws Zend_View_Exception
     */
    public function getTemplateEngine()
    {
        $currentEngine = $this->templateEngine;
        if ($this->hasTemplateEngine($currentEngine)) {
            return $this->templateEngines[$currentEngine];
        }

        $defaultEngine = $this->defaultTemplateEngine;
        if ($this->hasTemplateEngine($defaultEngine)) {
            return $this->templateEngines[$defaultEngine];
        }

        require_once 'Zend/View/Exception.php';
        throw new Zend_View_Exception('No template engine were found');
    }

    /**
     * Adds a template instance with key $key into
     * $templateEngines.
     *
     * @param string                    $key
     * @param App_View_Engine_Interface $templateEngine
     * @return App_View
     */
    public function addTemplateEngine($key, App_View_Engine_Interface $templateEngine)
    {
        $this->templateEngines[$key] = $templateEngine;
        return $this;
    }

    /**
     * Processes a view script and returns the output.
     * Hack for Zend_Layout => force the view suffix
     * depending on template engine
     *
     * @param string $name The script name to process.
     * @return string The script output.
     */
    public function render($name)
    {
        // hack for Zend_Layout which has its own view suffix handling
        $filename = $name;
        $suffix = $this->getTemplateEngine()->getViewSuffix();
        $fileParts = pathinfo($name);
        $filename = str_replace('.' . $fileParts['extension'], '.' . $suffix, $name);

        return parent::render($filename);
    }

    /**
     * Tells to the default template engine to render the template
     * if one is set.
     * Include the file otherwise (default behaviour)
     *
     * @param string The script filename to render
     */
    public function _run()
    {
        $filename = func_get_arg(0);
        $this->getTemplateEngine()->render($filename, get_object_vars($this));
    }
}