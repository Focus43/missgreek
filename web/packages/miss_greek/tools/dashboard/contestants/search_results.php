<?php defined('C5_EXECUTE') or die("Access Denied.");

    $permissions = new Permissions( Page::getByPath('/dashboard/miss_greek/contestants') );

    if( $permissions->canViewPage() ){
        $controller  = Loader::controller('/dashboard/miss_greek/contestants');
        $listObject  = $controller->contestantsListObj();
        $listResults = $listObject->getPage();

        Loader::packageElement('dashboard/contestants/search_results', 'miss_greek', array(
            'searchInstance'	=> $searchInstance,
            'listObject'		=> $listObject,
            'listResults'		=> $listResults,
            'pagination'		=> $pagination
        ));
    }