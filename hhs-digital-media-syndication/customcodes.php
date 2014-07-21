(function() {
    tinymce.create('tinymce.plugins.hhssynd', {
        init : function(ed, url) {
            console.log(url);
            ed.addButton('hhssynd', {
                title : 'Add HHS Syndicated Content',
                image : url+'/button.png',
                onclick : function() {
                    // triggers the thickbox
                    var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    console.log("width: " + width);
                    console.log("H: " + H);
                    console.log("W: " + W);
                    W = W - 80;
                    H = H - 84;
                    tb_show("HHS Digital Media Syndication", "<?php echo $_GET["pluginsurl"]; ?>/hhs-digital-media-syndication/popover.php?");
		}
            });
            
        },
        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('hhssynd', tinymce.plugins.hhssynd);

})();
