/*
	click handler for SexyBookmarks
	Credit: Phong Thai Cao - http://www.JavaScriptBank.com
	Please keep this creadit when you use this code
*/
jQuery(document).ready(function() {

	// xhtml 1.0 strict way of using target _blank
	jQuery('.sexy-bookmarks a.external').attr("target", "_blank");

	// this block sets the auto vertical expand when there are more than 
	// one row of bookmarks.
	var sexyBaseHeight=jQuery('.sexy-bookmarks').height();
	var sexyFullHeight=jQuery('.sexy-bookmarks ul.socials').height();
	if (sexyFullHeight>sexyBaseHeight) {
		jQuery('.sexy-bookmarks-expand').hover(
			function() {
				jQuery(this).animate({
						height: sexyFullHeight+'px'
				}, {duration: 400, queue: false});
			},
			function() {
				jQuery(this).animate({
						height: sexyBaseHeight+'px'
				}, {duration: 400, queue: false});
			}
		);
	}
	
	/*
		click handler for SexyBookmarks
		Credit: Cao Phong - http://www.JavaScriptBank.com
		Please keep this credit when you use this code
	*/
	jQuery('.sexy-bookmarks a.external').click(function() {
		var url = encodeURIComponent(window.location.href), desc = '';
		if( jQuery('p.sexy-bookmarks-content').length ) {
			desc = encodeURIComponent(jQuery('p.sexy-bookmarks-content').text());
		}
		switch(this.parentNode.className) {
			case 'sexy-digg':
				this.href += '?phase=2&title=' + document.title + '&url=' + url + '&desc=' + desc;
				break;
			case 'sexy-twitter':
				this.href += '?status=RT+@your_twitter_id:+' + document.title + '+-+' + url;
				break;
			case 'sexy-delicious':
				this.href += '?title=' + document.title + '&url=' + url;
				break;
			case 'sexy-facebook':
				this.href += '?t=' + document.title + '&u=' + url;
				break;
			case 'sexy-google':
				this.href += '?op=add&title=' + document.title + '&bkmk=' + url;
				break;
		}
	})
});

