<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2018 Splash Sync  <www.splashsync.com>
 *
 *  @author Splash Sync <contact@splashsync.com>
 *  @author Jeremy Barthe <j.barthe@lexik.fr>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\SonataAdminMonologBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Admin Class for Database Logs.
 */
class LogAdmin extends Admin
{
    /**
     * The number of result to display in the list.
     *
     * @var int
     */
    protected $maxPerPage = 50;

    /**
     * Default values to the datagrid.
     * On Ecrase l'ordre des donnée pour avoir les derniers RDV en Premier.
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'datetime',
    );

    /**
     * {@inheritdoc}
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('edit');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Message', array('class' => 'col-xs-12'))
            ->add('message', null, array('label' => 'log.results.message'))
            ->end()

            ->with('General', array('class' => 'col-xs-12 col-md-6'))
            ->add('datetime', null, array('label' => 'log.results.datetime'))
            ->add('channel')
            ->add('level_name', null, array(
                'label' => 'log.search.level',
                'template' => '@SplashSonataAdminMonolog/Admin/show__level.html.twig',
            ))
            ->end()

            ->with('Similar', array('class' => 'col-xs-12 col-md-6'))
            ->add('similar', null, array(
                'template' => '@SplashSonataAdminMonolog/Admin/show__similar.html.twig',
            ))
            ->end()

            ->with('Extras')
            ->add('formated', null, array(
                'template' => '@SplashSonataAdminMonolog/Admin/show__extra.html.twig',
            ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('datetime', null, array('label' => 'log.results.datetime'))
            ->add('level_name', 'actions', array(
                'label' => 'log.search.level',
                'actions' => array(
                    'status' => array(
                        'template' => '@SplashSonataAdminMonolog/Admin/list__level_view.html.twig',
                    ),
                ),
            ))
            ->add('channel')
            ->add('similarCount', 'actions', array(
                'label' => 'log.show.similar',
                'actions' => array(
                    'status' => array(
                        'template' => '@SplashSonataAdminMonolog/Admin/list__similar_view.html.twig',
                    ),
                ),
            ))

            ->addIdentifier('message', null, array('label' => 'log.results.message'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //====================================================================//
        // Connect To Repository
        $logsRepository = $this->getModelManager()
            ->getEntityManager($this->getClass())
            ->getRepository($this->getClass());

        //====================================================================//
        // Fetch Logs Channels
        $logsChannels = $logsRepository->getLogsChannels();
        //====================================================================//
        // Fetch Logs Levels
        $logsLevels = $logsRepository->getLogsLevels();

        $datagridMapper

            //====================================================================//
            // Add Logs Levels Filter
            ->add('level', 'doctrine_orm_choice', array(
                'show_filter' => true,
                'field_type' => ChoiceType::class,
                'field_options' => array(
                    'choices' => $logsLevels,
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                ),
            ))

            //====================================================================//
            // Add Logs Channels Filter
            ->add('channel', 'doctrine_orm_choice', array(
                'field_type' => ChoiceType::class,
                'field_options' => array(
                    'choices' => $logsChannels,
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                ),
            ))

            //====================================================================//
            // Add Message Filter
            ->add('message')
            ->add('formated', null, array(
                'label' => 'log.show.extra',
            ))

//            //====================================================================//
//            // Add Date Range Filter
//            ->add('datetime', 'doctrine_orm_date_range', array(
//                "label" => "log.results.datetime"
//            ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
    }
}
