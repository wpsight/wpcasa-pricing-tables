jQuery(document).ready(function($){
    var fieldGroupId = 'pricing_plans';
    var $fieldGroupTable = $( document.getElementById( fieldGroupId + '_repeat' ) );

    var countRows = function() {
        return $fieldGroupTable.find( '> .cmb-row.cmb-repeatable-grouping' ).length;
    };

    var disableAdder = function() {
        $fieldGroupTable.find('.cmb-add-group-row.button-secondary').prop( 'disabled', true );
    };

    var enableAdder = function() {
        $fieldGroupTable.find('.cmb-add-group-row.button-secondary').prop( 'disabled', false );
    };

    if ( countRows() >= limit ) {
        disableAdder();
    }

    $fieldGroupTable
        .on( 'cmb2_add_row', function() {
            if ( countRows() >= limit ) {
                disableAdder();
            }
        })
        .on( 'cmb2_remove_row', function() {
            if ( countRows() < limit ) {
                enableAdder();
            }
        });
});