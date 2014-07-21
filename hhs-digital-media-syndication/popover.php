<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HHS Digital Media Syndication</title>
        <link rel="stylesheet" href="../wp-content/plugins/hhs-digital-media-syndication/css/tree/style.css" />
        <script src="../wp-content/plugins/hhs-digital-media-syndication/js/jstree.js"></script>
        <script src="../wp-content/plugins/hhs-digital-media-syndication/js/jquery.maskedinput.js"></script>
        <script src="../wp-content/plugins/hhs-digital-media-syndication/js/module.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                function getConfigureParamsAsQueryString() {
                    var queryString = "";
                    var delim = "";
                    //TODO: Need to figure out how to support this w/ all sources, not just CDC.
                    if ($('#cdccs_stripimages').prop('checked')) {
                        queryString += delim + "stripImage=true";
                        delim = "&";
                    }
                    if ($('#cdccs_stripscripts').prop('checked')) {
                        queryString += delim + "stripScript=true"; //TODO: Need to figure if this is supported w/ API
                        delim = "&";
                    }
                    if ($('#cdccs_stripanchors').prop('checked')) {
                        queryString += delim + "stripAnchor=true";
                        delim = "&";
                    }
                    if ($('#cdccs_stripcomments').prop('checked')) {
                        queryString += delim + "stripComment=true"; //TODO: Need to figure if this is supported w/ API
                        delim = "&";
                    }
                    if ($('#cdccs_stripinlinestyles').prop('checked')) {
                        queryString += delim + "stripStyle=true";
                        delim = "&";
                    }
                    var encoding = $('#cdccs_encoding option:selected').val();
                    if (encoding) {
                        queryString += delim + "oe=" + encoding;
                        delim = "&"
                    }
                    return queryString;
                };
                
                $('#TB_title').append('<a id="insertSyndShortcode">Insert Media Shortcode</a>');
                
                $('#insertSyndShortcode').click(function(){
                    var qs = getConfigureParamsAsQueryString();
                    var contentId = $('#cdccs_title').val();
                   
                    var hideTitle = "false";
                    if ($('#cdccs_hidetitle').prop('checked')) {
                        hideTitle = "true";
                    }
                    var hideDescription = "false";
                    if ($('#cdccs_hidedescription').prop('checked')) {
                        hideDescription = "true";
                    }
                    
                    var source = $('#cdccs_source').val();
                    var mediaUrl = "";
                    if (HHSSourceData) {
                        for (var i = 0; i < HHSSourceData.length; i++) {
                            if (source === HHSSourceData[i].value) {
                                mediaUrl = HHSSourceData[i].mediaUrl;
                                break;
                            }
                        }   
                    }
                    
                    mediaUrl = mediaUrl.replace("{mediaid}", contentId);
                    mediaUrl += "?"+qs;
                    
                    var shortcode = '[hhs-syndication url="' + mediaUrl + '" contentid="' + contentId + '" hidetitle="' + hideTitle + '" hidedescription="' + hideDescription + '"]';
                    tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
                    tb_remove();
                });
                
                $("#cdccs_toggleIndicator").click(function(){
                    $("#cdccs_toggleIndicator").toggleClass("cdccs_collapsed cdccs_expanded");
                    $("#cdccs_displayoptions").toggle();
                });
            });
        </script>
        <style>
            form div {
                margin-bottom: 10px;
            }
        </style>
        <style>
            
            .cdccs_throbber {
                background: url("../wp-content/plugins/hhs-digital-media-syndication/css/tree/throbber.gif") no-repeat left top;
                width: 16px;
                height: 16px;
            }
            
            #cdccs_toggleIndicator {
                cursor: pointer;
                color: blue;
                padding-left: 10px;
            }
            
            #cdccs_toggleIndicator.cdccs_collapsed:before {
                content: "(show)";
            }
            
            #cdccs_toggleIndicator.cdccs_expanded:before {
                content: "(hide)";
            }
            
            #cdccs_displayoptions.cdccs_display_options_hidden {
                display: none;
            }
            
            #cdccs_displayoptions {
                clear: both;
                display:none;
            }
            
            #cdccs_displayoptions div {
                display: inline-block;
            }
            
            #cdccs_topictree_wrapper {
                float: right;
                width: 60%;
            }
            
            /* Wordpress specific sizing */
            #TB_ajaxContent {
                height: 90% !important;
                width: 95% !important;
            }
            /* Style for wordpress insert shortcode button */
            #insertSyndShortcode {
                float: right;
                height: 100%;
                padding-top: 5px;
                font-weight: bold;
                font-size: 13px;
                cursor:pointer;
                margin-right: 10px;
            }
            #insertSyndShortcode:hover, #insertSyndShortcode:active {
                color: red !important;
            }
            /* Makes ure the title select box doesn't blow out the popover width in Wordpress */
            #cdccs_title {
                max-width: 90%;
                overflow: hidden;
            }
            
            #cdccd_titleWrap {
                padding: 5px;
                clear:both;
                width: 100%;
                background-color: lightskyblue;
                border: 1px solid darkblue;
            }
            
            #cdccs_fromdate {
                width: 146px;
            }
            
            #cdccs_topictree {
                border: 1px dotted lightgray;
                height: 150px;
                overflow-y: scroll;
            }
            
            h3#cdccs_display_options_title, h3#cdccs_contentpreview_title {
                border-bottom: 1px solid lightgray;
            }
            
            #cdccd_titleWrap label {
                font-weight: bold;
            }
        </style>
    </head>
    <body id="cdccs">
        <noscript>Javascript is required for this to function properly</noscript>
        <h3 id="cdccs_display_options_title">Search For Content</h3>
        <form id="cdccs_contentpicker" action="#" method="post">
             <div id="cdccs_topictree_wrapper">
                <span>Topics:</span>
                <div id="cdccs_topictree">
                </div>
            </div>
            <div>
                <label for="cdccs_source">Source:</label>
                <select id="cdccs_source" name="cdccs_source">
                </select>
            </div>
            <div>
                <label for="cdccs_fromdate">From Date:</label>
                <input type="text" id="cdccs_fromdate"/>
            </div>
            <div>
                <label for="cdccs_mediatypes">Media Types:</label>
                <select id="cdccs_mediatypes" multiple>
                </select>
            </div>
           
            <div id="cdccd_titleWrap">
                <label for="cdccs_title">Title:</label>
                <select id="cdccs_title" name="cdccs_title">
                </select>
            </div>
        </form>
        
        <h4>Content Display Options<span id="cdccs_toggleIndicator" class="cdccs_collapsed"></span></h4>
        <form id="cdccs_displayoptions" action="#" method="post">
            
            <div>
                <label for="cdccs_stripimages">Strip Images:</label>
                <input type="checkbox" id="cdccs_stripimages"/>
            </div>    
            <div>
                <label for="cdccs_stripscripts">Strip Scripts:</label>
                <input type="checkbox" id="cdccs_stripscripts"/>
            </div>    
            <div>
                <label for="cdccs_stripanchors">Strip Anchors:</label>
                <input type="checkbox" id="cdccs_stripanchors"/>
            </div>    
            <div>
                <label for="cdccs_stripcomments">Strip Comments:</label>
                <input type="checkbox" id="cdccs_stripcomments"/>
            </div>    
            <div>
                <label for="cdccs_stripinlinestyles">Strip Inline Styles:</label>
                <input type="checkbox" id="cdccs_stripinlinestyles"/>
            </div>
            <div>
                <label for="cdccs_hidetitle">Hide Title</label>
                <input type="checkbox" id="cdccs_hidetitle" name="cdccs_hidetitle"/>
            </div>    
            <div>
                <label for="cdccs_hidedescription">Hide Description</label>
                <input type="checkbox" id="cdccs_hidedescription" name="cdccs_hidedescription"/>
            </div>    
            <div>
                <label for="cdccs_encoding">Encoding</label>
                <select id="cdccs_encoding" name="cdccs_encoding">
                    <option value="">Default</option>
                    <option value="utf-8">UTF-8</option>
                    <option value="iso-8859-1">iso-8859-1</option>
                </select>
            </div>
        </form>
        <h3 id="cdccs_contentpreview_title">Content Preview</h3>
        <div id="cdccs_preview"></div>
    </body>
</html> 

