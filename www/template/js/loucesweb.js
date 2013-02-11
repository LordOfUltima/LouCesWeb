/**
 * @package    LouCesWeb
 * @subpackage JavaScript
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 08-Feb-2013
 * @license    GNU/GPL
 */


jQuery(function($) {
    // Submit-Button
    $("#submitBtn").button();
    // clear Cache
    $("#clearcacheBtn").button().click(function() {
        $('#target').submit();
    });
    // advance
    $("#advanceBtn").button().click(function() {
        //$('#tabs').toggle($(this).checked);
        $('#target').submit();
    });
    // Radios
    $("#radioset_hours").buttonset();
    $("#radioset_hours").find('input:radio').click(function() {
        $('#target').submit();
    });
    // Tabs
    $('#tabs').tabs({collapsible: true, cookie: { }});
    // Accordions
    $('#tabs div.accordions').each(function(index) {
        var cookieName = 'ui-accordion-'+ index;
        var activeHeader = parseInt($.cookie(cookieName) || 0);
        $(this).accordion(
            {
                autoHeight: false,
                clearStyle: true,
                header: "h3",
                collapsible: true,
                active: activeHeader,
                change: function(e, ui)
                {
                    $.cookie(cookieName, $(this).find("h3").index(ui.newHeader[0]));
                }
            }
        );
        // Choosen
        $("#statSelect").chosen({disable_search_threshold:999}).change(function(){
            //console.log($(this).val());
        });
    });
    // Autocomplete
    var cache = {}, lastXhr;
    $("#autoName").autocomplete({
        minLength: 2,
        source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }
            lastXhr = $.getJSON( "?do=userquery", request, function( data, status, xhr ) {
                cache[ term ] = data;
                if ( xhr === lastXhr ) {
                    response( data );
                }
            });
        }
    });
});
