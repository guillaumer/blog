
$(document).ready(function(){  
    
    //Chemin absolu vers /public/images
    base_url = $("div#breadcrumbs_content img").attr("src");
    base_url = base_url.split('/');
    baseUrl = '';
    for (i=0;  i<base_url.length-1; i++){
        baseUrl += base_url[i]+'/';
    }
    finalUrl = 'http://'+window.document.domain+baseUrl;
    
    
    $('#last_articles').pajinate({
            nav_label_first : '',
            nav_label_last : '',
            nav_label_prev : '&nbsp;',
            nav_label_next : '&nbsp;',
            items_per_page : 5,
            item_container_id : '.ul_content',
            nav_panel_id : '.page_navigation'
    });
    if ($('#last_articles ul li').size() < 5){
        $('#last_articles .page_navigation').remove();
    }
    $('#last_comments').pajinate({
            nav_label_first : '',
            nav_label_last : '',
            nav_label_prev : '&nbsp;',
            nav_label_next : '&nbsp;',
            items_per_page : 5,
            item_container_id : '.ul_content',
            nav_panel_id : '.com_page_navigation'
    });
    if ($('#last_comments ul li').size() < 5){
        $('#last_comments .com_page_navigation').remove();
    }
    $('#last_tags').pajinate({
        nav_label_first : '',
        nav_label_last : '',
        nav_label_prev : '&nbsp;',
        nav_label_next : '&nbsp;',
        items_per_page : 5,
        item_container_id : '.ul_content',
        nav_panel_id : '.alt_page_navigation'
    });
    if ($('#last_articles ul li').size() < 5){
        $('#last_articles .alt_page_navigation').remove();
    }
    $('#post_it ul li span.note_content').live('click', function(){
        $(this).editable('manage-notes', {
            tooltip   : 'Cliquer pour modifier',
            indicator : '<img src="../../public/images/spinning_icon.gif">'
        })
    });
    $('#post_it ul li span.note_content').editable('manage-notes', {
            tooltip   : 'Cliquer pour modifier',
            indicator : '<img src="../../public/images/spinning_icon.gif">'
    });
    //Lien pour ajouter une note
    $('#post_it a.ajouter_note').click(function(event) {
        event.preventDefault();
        var author = $('#post_it ul').attr("id");
        $('#post_it ul').append(
            '<li><span class="note_content">Nouvelle note</span><span class="delete_note"><a href="#"><img src="'+finalUrl+'delete.png" alt="Supprimer"/></a></span></li>'
        );
        $.ajax({
            type: "POST",
            url: "getlast-note",
            success: function(newElement){
                content = $('#post_it ul li:last-child span.note_content').text();
                $('#post_it ul li:last-child span.note_content').trigger('click');
                $('#post_it ul li:last-child span.note_content').attr('id', parseInt(newElement)+1);
                id = $('#post_it ul li:last-child span.note_content').attr('id');
                $.ajax({
                    type: "POST",
                    url: "manage-notes",
                    data: "id="+id+"&value="+content,
                    success: function(newElement){
                    }
                });
            }
        });
        $('#post_it ul li:last-child span.note_content').editable('manage-notes', {
            tooltip   : 'Cliquer pour modifier...',
            indicator : '<img src="../../public/images/spinning_icon.gif">'
        });
    });

    //Supprimer une note
    $('#post_it span.delete_note a').live('click', function(e){
        e.preventDefault();
        var $target = $(e.target);
        var id = $(this).parent().parent().find('span.note_content').attr("id");
        $.ajax({
            type: "POST",
            url: "delete-note",
            data: "id="+id,
            success: function(newElement){
                $target.parent().parent().parent().slideUp(300);
            }
        });
    });

    //Liens
    $(function() {
            $("#liens ul").sortable({
               connectWith: '#delete_link',
               start: function(event, ui) {
                    $('<div id="delete_area"></div>').appendTo("#liens").droppable({
                         accept: '#liens ul li',
                         hoverClass: 'delete_area_hover',
                         drop: function(event, ui) {
                            deleteImage(ui.draggable,ui.helper);
                         },
                         activeClass: 'delete_area_hover'
                     });
               },
               update: function(event, ui) {
                   var elems = $(event.target).children();
                   $(elems).each(function(i) {
                       element = $(this);
                       index = $(this).index();
                       identifiant = $(this).attr("id");
                       id = identifiant.split('-');
                       id = id[0];
                       $.ajax({
                            type: "POST",
                            url: "update-links",
                            data: "id="+id+"&index="+index
                        });
                       $(this).attr('id', id+'-'+$(this).index());
                       $(this).children('span.lien_ordre').text($(this).index());
                   });
               },
               stop: function(event, ui) {
                   $("#liens div#delete_area").remove();
               }
            });
             function deleteImage($draggable,$helper){
                identifiant = $draggable.attr('id');
                id = identifiant.split('-');
                id = id[0];
                $.ajax({
                    url: 'delete-link',
                    type: 'POST',
                    data: 'id='+id
                 });
                $helper.effect('transfer', {to: '#delete_area', className: 'ui-effects-transfer'},500);
                $draggable.remove();
            }
            $("#liens ul").disableSelection();
    });
    
    //Ajouter un lien
    $('a.ajouter_lien').click(function(event) {
           event.preventDefault();
            var form = '<form style="display:none" id="form_ajout_lien" name="ajout_lien" method="post"> Adresse <span class="lien_add_content">http://</span>';
            form += '   <input type="text" size="20" id="url"/><br/>';
            form += 'Nom <input type="text" size="20" id="name"/><br/>';
            form += 'Description <input type="text" size="40" id="desc"/>';
            form += '<input type="submit" id="lien_add_ok" value=""/>';
            form += '<a href="#" id="lien_add_cancel">Annuler</a></form>';
            $('div#titre_liens').after(form);
            $('#form_ajout_lien').slideDown('fast');
    });
    $('#lien_add_ok').live('click', function(e) {
         e.preventDefault();
         url = 'http://'+$('#url').val();
         name = $('#name').val();
         desc = $('#desc').val();
         lien_ajout(url, name,desc);
    });
     $('#lien_add_cancel').live('click', function(e) {
         e.preventDefault();
         $(this).parent().slideUp('fast', function(){
             $(this).remove();
         });
     });
     function lien_ajout(url, name, desc){
            order = $('#liens ul li:last-child span.lien_ordre').text();
            new_order = parseInt(order)+1;
            id = new_order+1;
            if (!new_order){
                new_order = '0';
            }
            if ((url != '') && (name != '')) {
                $('#liens ul').append(
                    '<li id="'+id+'-'+new_order+'"><span class="url"><a href="'+url+'">'+name+'</a></span><span class="lien_ordre">'+new_order+'</span>'
                );
                $.ajax({
                    type: "POST",
                    url: "add-link",
                    data: "index="+new_order+"&url="+url+"&name="+name+"&desc="+desc,
                    success: function(newElement){
                        $('#liens ul li:last-child span.url').trigger('click');
                        $('#liens ul li:last-child').attr('id', newElement);
                    }
                });
                $('#lien_add_cancel').parent().slideUp('fast', function(){
                     $(this).remove();
                 });
            }
        }
});