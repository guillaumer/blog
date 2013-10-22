/* 
 *  Fichier commun à tout le blog.
 *  @author:Guillaume Raoult
 */

    $(document).ready(function() {
            
            /*
             * Menu
             */
            var bg_active = $("ul.navigation li.active").css("background");
            $("ul.navigation li").hover(function(){
                    if($(this).hasClass('active') == false) {
                        $("ul.navigation li.active").css("background","none");
                    }
            },
              function () {
                    $("ul.navigation li.active").css("background",bg_active);
            });
            
            /*
             *Lightboxes
             */
            $(".thumb").yoxview({
                autoHideInfo:false,
                autoHideMenu:false,
                lang:'fr'
            });            
            
    });
