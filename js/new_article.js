/* 
 */

$(document).ready(function() {
    //Chemin absolu vers /public/images
    base_url = $("#etat_modif img").attr("src");
    base_url = base_url.split('/');
    baseUrl = '';
    for (i=0;  i<base_url.length-1; i++){
        baseUrl += base_url[i]+'/';
    }
    finalUrl = 'http://'+window.document.domain+baseUrl;

    //Ajout de l'aide'
    $('#tags-submit').after('<div id="tag_tip">Séparer les mots clés avec une virgule</div>');

    /*
     * On a un ajout de tag, on envoie en AJAX pour récupérer l'élément de formulaire
     * caché et l'ajouter, ainsi que la div
     * avec l'intitulé et le bouton de suppression
     */
    url = window.document.location.pathname;
    url = url.split('/');
    if (url[url.length-1] == 'new-article') {
        ajax_url = 'get-tagsform';
    } else{
        ajax_url = '../get-tagsform';
    }
    $('#tags-submit').click(function(e) {
      e.preventDefault();
      elems = $('#tags-tag_add').val();
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: "tags="+elems,
                success: function(newElement){
                    $(newElement).children('input.tag_name').each(function(index, elem) {
                        form_elem = elem;
                        var val = trimAll($(this).val());
                        $('#fieldset-tags').append('<div id="'+val+'"><span class="tag_delete"><img width="13px" height="13px" src="'+finalUrl+'delete.png" alt="Supprimer"/></span><span class="tag_name">'+val+'</span></div>');
                        div = '#fieldset-tags div#'+$(this).val();
                        $(div).prepend(form_elem);
                    });
                    //Reset de l'input
                    $('#tags-tag_add').val('');
                }
            });
    });

    //Nettoie la chaine
    function trimAll(sString) {
        while (sString.substring(0,1) == ' ')
        {
            sString = sString.substring(1, sString.length);
        }
        while (sString.substring(sString.length-1, sString.length) == ' ')
        {
            sString = sString.substring(0,sString.length-1);
        }
        return sString;
    }

    /*
     * Click sur btn suppression tag
     */
    $(".tag_delete").live('click', function(e) {
          deleteTag(e);
        }
    );

    /*
     * Suppression div contenant intitulé tag + btn suppr
     */
    function deleteTag(e){
        $(e.target).parent().parent().fadeOut('fast', function() {
            $(this).remove();
        });
    }


    /*
     * Zones editables
     */

    /*Permalien*/
    $('span.article_name_edit').editable('../edit-name', {
                submit   : 'Valider',
                width:($('span.article_name_edit').width() + 10) + "px",
                height:'20px',
                tooltip   : 'Cliquer pour modifier le permalien',
                indicator : '<img src="../../../public/images/spinning_icon.gif">'
    });

    /*Etat auteur*/
    $("a#author_modif").live('click', function(e) {
            e.preventDefault();
            $("div#author_select").slideDown();

            $("a#author_ok").live('click', function(e) {
                    e.preventDefault();
                    value = $("#author option:selected").attr("label");
                    $("span#author_value").text(value);
                    $("div#author_select").slideUp();
            });
    });

    /*Etat publication*/
    $("a#etat_modif").live('click', function(e) {
            e.preventDefault();
            $("div#etat_select").slideDown();

            $("a#etat_ok").live('click', function(e) {
                    e.preventDefault();
                    value = $("#status option:selected").attr("label");
                    $("span#etat_value").text(value);
                    $("div#etat_select").slideUp();
            });
    });
    
    /*Etat type*/
    $("a#type_modif").live('click', function(e) {
            e.preventDefault();
            $("div#type_select").slideDown();

            $("a#type_ok").live('click', function(e) {
                    e.preventDefault();
                    value = $("#type option:selected").attr("label");
                    $("span#type_value").text(value);
                    $("div#type_select").slideUp();
            });
    });
    
    /*Etat featured*/
    $("a#featured_modif").live('click', function(e) {
            e.preventDefault();
            $("div#featured_select").slideDown();

            $("a#featured_ok").live('click', function(e) {
                    e.preventDefault();
                    value = $("#featured option:selected").attr("label");
                    $("span#featured_value").text(value);
                    $("div#featured_select").slideUp();
            });
    });


    /*date publication et modif*/
    pdate = $("#pdate").val();
    $( "#pdate" ).datepicker({
            showOn: "button",
            buttonImage: finalUrl+'icon_calendar.png',
            gotoCurrent: true,
            defaultDate: pdate,
            buttonImageOnly: true,
            onSelect: function(dateText, inst) {
                $(this).parent().find(".param_value").text(dateText);
            }
    });
    $( "#pdate").datepicker($.datepicker.regional['fr']);

    mdate = $("#mdate").val();
    $( "#mdate" ).datepicker({
            showOn: "button",
            buttonImage: finalUrl+'icon_calendar.png',
            gotoCurrent: true,
            defaultDate: mdate,
            buttonImageOnly: true,
            onSelect: function(dateText, inst) {
                $(this).parent().find(".param_value").text(dateText);
            }
    });
    $( "#mdate").datepicker($.datepicker.regional['fr']);

});


