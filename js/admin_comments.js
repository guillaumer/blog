$(document).ready(function(){
	
	$('a.btn_supprimer').click(function(event){
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
                                                        url: "delete-comment",
                                                        data: "id="+id,
                                                        success: function(newElement){
                                                            elem.hide(500);
                                                            //$('#table_comments').find('tr#'+id).hide(1000);
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

        $("span.comment_article").simpletip({ 
            // content: 'My Simpletip',
             position: 'left',
             persistent:true
        });


    
});
