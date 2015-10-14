<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Torneos\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Decks\Form\DecksForm;
use Decks\Model\Entity\Cards,
Decks\Model\Entity\Decks,
Decks\Model\Entity\DeckCards,
Decks\Model\Entity\Tournaments;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function verAction()
    {
        return new ViewModel();
    }
}

