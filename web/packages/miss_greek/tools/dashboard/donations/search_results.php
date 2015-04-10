<?php defined('C5_EXECUTE') or die("Access Denied.");

    $permissions = new Permissions( Page::getByPath('/dashboard/miss_greek/donations') );

    if( $permissions->canViewPage() ){
        $controller  = Loader::controller('/dashboard/miss_greek/donations');
        $listObject  = $controller->donationsListObj();
        $listResults = $listObject->getPage();

        Loader::packageElement('dashboard/donations/search_results', 'miss_greek', array(
            'searchInstance'	=> $searchInstance,
            'listObject'		=> $listObject,
            'listResults'		=> $listResults,
            'pagination'		=> $pagination
        ));
    }