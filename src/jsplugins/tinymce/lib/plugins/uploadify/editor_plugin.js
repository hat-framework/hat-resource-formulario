/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Thompson Moreira
 * Released under LGPL License.
 *
 */

(function() {
        // Load plugin specific language pack
        //tinymce.PluginManager.requireLangPack('example');

        tinymce.create('tinymce.plugins.UploadifyPlugin', {
                /**
                 * Initializes the plugin, this will be executed after the plugin has been created.
                 * This call is done before the editor instance has finished it's initialization so use the onInit event
                 * of the editor instance to intercept that event.
                 *
                 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
                 * @param {string} url Absolute URL to where the plugin is located.
                 */
                init : function(ed, url) {
                        
                        // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
                        ed.addCommand('uploadify', function() {
                                ed.windowManager.open({
                                        file : url + '/uploadify.php',
                                        width : 335 + ed.getLang('uploadify.delta_width', 0),
                                        height : 320 + ed.getLang('uploadify.delta_height', 0),
                                        inline : 1
                                }, {
                                        plugin_url : url // Plugin absolute URL
                                });
                        });

                        // Register example button
                        ed.addButton('uploadify', {
                                title : 'UploadifyPlugin',
                                cmd : 'uploadify',
                                image : url + '/img/box_upload.png'
                        });
                },

                /**
                 * Returns information about the plugin as a name/value array.
                 * The current keys are longname, author, authorurl, infourl and version.
                 *
                 * @return {Object} Name/value array containing information about the plugin.
                 */
                getInfo : function() {
                        return {
                                longname : 'Uploaify Plugin',
                                author : 'Thompson Moreira Filgueiras',
                                authorurl : 'http://plugins.origin-webmasters.com.br',
                                infourl : 'http://plugin.origin-webmasters.com.br/plugin/tinymce/uploadify',
                                version : "1.0"
                        };
                }
        });

        // Register plugin
        tinymce.PluginManager.add('uploadify', tinymce.plugins.UploadifyPlugin);
})();