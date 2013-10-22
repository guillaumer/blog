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

    $('#fieldset-tags fieldset').each(function(index, elem) {
            form_elem = elem;
            $(elem).remove();
            $('#fieldset-tags').append('<div id="'+$(elem).children().val()+'"><span class="tag_delete"><img width="13px" height="13px" src="'+finalUrl+'delete.png" alt="Supprimer"/></span><span class="tag_name">'+$(elem).children().val()+'</span></div>');
            div = '#fieldset-tags div#'+$(elem).children().val();
            $(div).prepend($(form_elem));
    });
        

});



