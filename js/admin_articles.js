$(document).ready(function(){
    
    //Chemin absolu vers /public/images
    base_url = $("div#breadcrumbs_content img").attr("src");
    base_url = base_url.split('/');
    baseUrl = '';
    for (i=0;  i<base_url.length-1; i++){
        baseUrl += base_url[i]+'/';
    }
    console.log(window.location.hostname);
    finalUrl = 'http://'+window.document.domain+baseUrl;
    
    $('a.btn_supprimer').live("click", function(event){
                event.preventDefault();
                url = $(this).attr("href");
                id = $(this).attr("href").split('/');
                id = id[1];
                var elem = $(this).closest('tr');
                $.confirm({
                        'title'		: '',
                        'message'	: 'Supprimer l\'article ?',
                        'buttons'	: {
                                'Supprimer'	: {
                                        'class'	: 'blue',
                                        'action': function(){
                                                  $.ajax({
                                                        type: "POST",
                                                        url: "delete-article",
                                                        data: "id="+id,
                                                        success: function(newElement){
                                                            elem.hide(1000);
                                                            $('#table_articles').find('tr#'+id).hide(1000);
                                                        }
                                                    });
                                        }
                                },
                                'Annuler'	: {
                                        'class'	: 'gray'
                                }
                        }
                });
        });

        $('div#articles_exist ul li').live("click", function(){
                $('div#articles_exist ul li.active').removeClass('active');
                var active = $(this);
                var classe = $(this).attr('class');
                classe = classe.split("_");
                id = classe[1];
                $.ajax({
                    url: 'articles',
                    type: 'GET',
                    data: 'id='+id,
                    success: function(article){
                            active.addClass('active');
                            $('#resume_article').remove();
                            var resume_article = '<div id="resume_article">'+article+'</div>';
                            $('#globals_params').html(resume_article);
                    }
              });
        }).ajaxStart(function() {
                $('#globals_params').html('<img src="'+finalUrl+'spinning_icon.gif">');
        });
})
