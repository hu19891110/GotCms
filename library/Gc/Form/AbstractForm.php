<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category   Gc
 * @package    Library
 * @subpackage Form
 * @author     Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license    GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link       http://www.got-cms.com
 */

namespace Gc\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Gc\Exception;
use Gc\Db\AbstractTable;

/**
 * Abstract Form overload Zend\Form\Form
 * This is better to initialize somes values, retrieve adapter
 * add dynamic content, etc...
 *
 * @category   Gc
 * @package    Library
 * @subpackage Form
 */
abstract class AbstractForm extends Form
{
    /**
     * Identifier pattern constante
     *
     * @const IDENTIFIER_PATTERN
     */
    const IDENTIFIER_PATTERN = '~^[a-zA-Z0-9_-]+$~';

    /**
     * Constructor
     *
     * @param null|string|int $name Optional name for the element
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');
        $this->setUseInputFilterDefaults(false);
        $this->init();
    }

    /**
     * Initialize form
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Get db adapter
     *
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getAdapter()
    {
        return \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
    }

    /**
     * Load values
     *
     * @param AbstractTable $table Table
     *
     * @return AbstractForm
     */
    public function loadValues(AbstractTable $table)
    {
        $data        = $table->getData();
        $inputFilter = $this->getInputFilter();
        if (is_array($data)) {
            foreach ($data as $elementName => $elementValue) {
                if ($this->has($elementName)) {
                    $element = $this->get($elementName);
                    $this->get($elementName)->setValue($elementValue);
                }

                if ($inputFilter->has($elementName)) {
                    $validators = $inputFilter->get($elementName)->getValidatorChain()->getValidators();

                    foreach ($validators as $validator) {
                        if ($validator['instance'] instanceof \Zend\Validator\Db\NoRecordExists) {
                            $validator['instance']->setExclude(array('field' => 'id', 'value' => $table->getId()));
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Add content to form
     *
     * @param Fieldset $form     Form
     * @param mixed    $elements Elements
     * @param string   $prefix   Add belong to for each elements
     *
     * @static
     * @return void
     */
    public static function addContent(Fieldset $form, $elements, $prefix = null)
    {
        if (empty($elements)) {
            return;
        }

        if (is_array($elements)) {
            foreach ($elements as $element) {
                self::addContent($form, $element, $prefix);
            }
        } elseif ($elements instanceof Element) {
            if (!empty($prefix)) {
                $id = $elements->getAttribute('id');
                if (empty($id)) {
                    $id = $elements->getAttribute('name');
                }

                $elements->setAttribute('id', $id . mt_rand());
                $elements->setAttribute('name', $prefix . '[' . $elements->getAttribute('name') . ']');
            }

            $form->add($elements);
        } elseif (is_string($elements)) {
            if (!empty($prefix)) {
                $randId   = mt_rand();
                $elements = preg_replace('~name="(.+)(\[.*\])?"~iU', 'name="' . $prefix . '[$1]$2"', $elements);
                $elements = preg_replace('~id="(.+)"~iU', 'id="${1}' . $randId . '"', $elements);
                $elements = preg_replace(
                    '~(?:(?!(?<=value=)))("|\')#(.+)("|\')~iU',
                    '${1}#${2}' . $randId . '${3}',
                    $elements
                );
            }

            $hiddenElement = new Element('hidden' . uniqid());
            $hiddenElement->setAttribute('content', $elements);
            $form->add($hiddenElement);
        } else {
            throw new Exception('Invalid element ' . __CLASS__ . '::' . __METHOD__ . ')');
        }
    }

    /**
     * Return element value
     *
     * @param string $name Name
     *
     * @return string
     */
    public function getValue($name = null)
    {
        if ($this->has($name)) {
            return $this->get($name)->getValue();
        }

        return null;
    }
}