<?php
/**
 * This source file is part of Got CMS.
 *
 * Got CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Got CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with Got CMS. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category Controller
 * @package  Content\Controller
 * @author   Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license  GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link     http://www.got-cms.com
 */

namespace Content\Controller;

use Gc\Mvc\Controller\Action,
    Gc\Document\Collection as DocumentCollection,
    Content\Form,
    Gc\Component,
    Gc\Core\Translator,
    Zend\Json\Json;

class TranslationController extends Action
{
    /**
     * Contains information about acl
     * @var array $_acl_page
     */
    protected $_acl_page = array('resource' => 'Content', 'permission' => 'translation');

    /**
     * Initialize Document Controller
     * @return void
     */
    public function init()
    {
        $documents = new DocumentCollection();
        $documents->load(0);

        $this->layout()->setVariable('treeview',  Component\TreeView::render(array($documents)));

        $routes = array(
            'edit' => 'documentEdit'
            , 'new' => 'documentCreate'
            , 'delete' => 'documentDelete'
            , 'copy' => 'documentCopy'
            , 'cut' => 'documentCut'
            , 'paste' => 'documentPaste'
        );

        $array_routes = array();
        foreach($routes as $key => $route)
        {
            $array_routes[$key] = $this->url()->fromRoute($route, array('id' => 'itemId'));
        }

        $this->layout()->setVariable('routes', Json::encode($array_routes));
    }

    /**
     * Create document
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function createAction()
    {
        $translation_form = new Form\Translation();
        $translation_form->setAttribute('action', $this->url()->fromRoute('translationCreate'));

        if($this->getRequest()->isPost())
        {
            $post = $this->getRequest()->getPost();
            $translation_form->setData($post->toArray());
            if($translation_form->isValid())
            {
                $source = $post->get('source');
                $data = array();
                foreach($post->get('destination') as $destination_id => $destination)
                {
                    $data[$destination_id] = array('value' => $destination);
                }

                foreach($post->get('locale') as $locale_id => $locale)
                {
                    if(empty($data[$locale_id]))
                    {
                        continue;
                    }

                    $data[$locale_id]['locale'] = $locale;
                }

                Translator::setValue($source, $data);
            }
        }

        return array('form' => $translation_form);
    }

    /**
     * Delete document
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function deleteAction()
    {

    }

    /**
     * Edit Document
     *
     * @return \Zend\View\Model\ViewModel|array
     */
    public function indexAction()
    {
        return array('values' => Translator::getValues());
    }
}
