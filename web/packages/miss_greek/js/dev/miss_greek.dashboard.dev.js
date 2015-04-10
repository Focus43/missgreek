 $(function(){
     window.MGDashboard = (function(){

         var $document    = $(document),
             $mgContainer = $('#mg-dashboard');


         /**
          * Tabs.
          */
         $('.nav-tabs').on('click', '[data-toggle]', function( _clickEv ){
             _clickEv.preventDefault();
             var $clicked = $(this);
             $clicked.parents('li').addClass('active').siblings('li').removeClass('active');
             $( $clicked.attr('href') ).addClass('active').siblings('.tab-pane').removeClass('active');
         });


         /**
          * Delete contestant button.
          */
         $('#btnDeleteContestant').on('click', function(){
             var $clicked = $(this);
             if( confirm('Really delete this contestant? This cannot be undone.') ){
                 window.location = $clicked.attr('data-src');
             }
         });


         /**
          * Toggle all checkboxes on the search page.
          */
         $mgContainer.on('click', '#chkToggleAll', function(){
             var checkd = $(this).is(':checked');
             $('.chk-multi-action', $mgContainer).prop('checked', checkd);
             $('#searchMultiMenu').prop('disabled', !checkd);
         });


         /**
          * If at least one results checkbox is checked, enable the dropdown menu (search page).
          */
         $mgContainer.on('change', '.chk-multi-action', function(){
             var checkd = $('.chk-multi-action', $mgContainer).filter(':checked').length ? true : false;
             $('#searchMultiMenu').prop('disabled', !checkd);
         });


         /**
          * Multi-action menu on the search page.
          */
         $document.on('change', '#searchMultiMenu', function(){
             var $menu    = $(this),
                 $checked = $('.chk-multi-action', $mgContainer).filter(':checked'),
                 _data    = $checked.serializeArray();

             switch( $menu.val() ){
                 case 'delete':
                     if( confirm('Delete selected contestants? This cannot be undone.') ){
                         $.post( $menu.attr('data-delete'), _data, function(resp){
                             if( resp.code === 1 ){
                                 $checked.parents('tr').fadeOut(150, function(){
                                     $(this).remove();
                                 });
                                 ccmAlert.hud('Deleted '+$checked.length+' Contestants', 2000, 'success');
                             }else{
                                 ccmAlert.hud('Error Deleting Contestants', 2000, 'error');
                             }
                         }, 'json');
                     }
                     break;
             }

             // reset to unselected
             $menu.val('');
         });


         // @public methods
         return {

         }
     })();
 });