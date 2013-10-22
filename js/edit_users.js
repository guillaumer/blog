/* 
 * JS pour la page d'admin de modif des utilisateurs
 * @author:GR
 * @date:06/08/11
 */

$(document).ready(function(){
            draganddrop();
            var base_url = $("#breadcrumbs_content img").attr("src");
            base_url = base_url.split('/');
            baseUrl = '';
            for (i=0;  i<base_url.length-1; i++){
                baseUrl += base_url[i]+'/';
            }
            finalUrl = 'http://'+window.document.domain+baseUrl;
            var sauvegarde_contenu = $('.params').html();
            //affichage des infos sur un utilisateurs
            $('.user_box').ajaxStart(function() {
                //$('div#user_select_list').css('width','600px');
                //$('div#user_edit_infos').slideDown();
                //$('div#user_edit_infos').html('Triggered ajaxStart handler.');
            });
            /*$('.user_box').ajaxStop(function() {
                $('div#user_edit_infos').html('loaded.');
            });*/
            $('.user_box').click(function(event){
                    event.preventDefault();
                    var author_id = $(this).children('.user_id').text();
                    var author_username = $(this).children('.user_info').children('.user_username').text();
                    $.ajax({
                        type: "POST",
                        url: "user-infos",
                        data: "user="+author_id+'&username='+author_username,
                        success: function(newElement){
                            afficheUtilisateur(newElement);
                        }
                    });
            });
            
            function afficheUtilisateur(data){
                $('div.params').fadeOut('fast', function() {
                    $('h2.titre_sidebar').html()
                    $('div.params').width('350px');
                    $('div.params').html(data);
                    $('div.params').fadeIn();
                });
            }
            
            
            //Modification des groupes
            $('a.lien_modif').click(function(event){
                    event.preventDefault();
                    $(this).parent().children('ul').slideDown();
                    $(this).children().css('padding-top','5px');
                    //On rend le nom du groupe modifiable
                    var nom_gp = $(this).parent().children('.nom_groupe');
                    var previous_group = nom_gp.html();
                    nom_gp.editable('edit-group', {
                                submit   : 'Valider',
                                width:(nom_gp.width() + 10) + "px",
                                height:'15px',
                                tooltip   : 'Cliquer pour modifier le nom du groupe',
                                indicator : '<img src="'+finalUrl+'spinning_icon.gif">',
                                callback : function(value, settings) {
                                    $('a.lien_modif').children().css('padding-top','0px');
                                    if($('.user_status') .html() == previous_group){
                                        $('.user_status').html(value);
                                    }
                                    if($('#user_status').html() == previous_group){
                                        $('#user_status').html(value);
                                    }
                                }
                    });
                    nom_gp.click();
            });
            
            //bouton retour paramètres
            $('#affichage_precedent').live('click', function() {
                $('div.params').fadeOut('fast', function() {
                    $('h2.titre_sidebar').html('Groupes');
                    $('div.params').width('250px');
                    $('div.params').html(sauvegarde_contenu);
                    $('div.params').fadeIn();
                    draganddrop();
                });
            });
            
            function draganddrop(){
                //Affectation des utilisateurs aux groupes / drag&drop
                $('.user_box').draggable({
                        revert: 'invalid',
                        cursor: "move",
                        cursorAt: { top: -5, left: -10 },
                        helper: function(event) {
                                var img = $(this).children('img');
                                img.clone().prependTo($(this));
                                img.css('z-index','100');
                                return img;
                        }
                });
                $('#group_list > li').droppable({
                        hoverClass: "user_hover_drop",
                        activeClass: "user_active_drop",
                        drop: function (event, ui) {
                            $.ajax({
                                type: "POST",
                                url: "edit-user-group",
                                data: "user=",
                                success: function(newElement){
                                    console.log(newElement);
                                }
                            });
                        }
                });
            }
});



