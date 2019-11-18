/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
    jQuery(document).ready(function(){
        $("#place_tree").tree({
            ui : {
                theme_name : "default"

            },
            plugins : {
                cookie : { prefix : "jstree_" }
            },
            rules : {
                // only nodes of type root can be top level nodes
                valid_children : [ "root" ],
                drag_copy: false
            },
            types : {

                "root" : {
                    draggable : false,
                    valid_children : [ "folder" ]
                },
                "file" : {
                    // the following three rules basically do the same
                    valid_children : "none",
                    max_children : 0,
                    max_depth :0,
                    icon : {
                        image : "/demos/file.png"
                    }
                },
                'category': {
                    valid_children : [ "file" ]
                }
            },
            callback:{
                onmove: function(NODE, REF_NODE, TYPE){
                    
                    var itsNode = substr($(NODE).attr('id'));
                    var itsRefNode = substr($(REF_NODE).attr('id'));

                    $.post('http://'+document.location.hostname+'/admin/place/content/onmove', {
                        node_id: itsNode.id,
                        node_type: itsNode.type,
                        ref_node_id: itsRefNode.id,
                        ref_node_type: itsRefNode.type,
                        onmove_type: TYPE
                    });
                },
                onselect: function(NODE){

                    var item = $('a', $(NODE)).get();
                    var dataString = $(item).attr('item')
                    var dataArray = dataString.split(':');

                    switch(dataArray[0])
                    {
                        case 'f':
                        case 'c':
                            $.post($(item).attr('href'),{
                                id:dataArray[1],
                                item:dataArray[0]
                            },function(data){
                                $('#place_content').html(data);
                            });
                            break;
                    }
                }/*,
                onsearch : function (n,t) {
                    t.container.find('.search').removeClass('search');
                    n.addClass('search');
                }   */
            }
        });
    });
    
	function substr(string)
    {
        var type = string.substr(0, 1);
        var id = string.substr(1);
        
        return {type:type, id:id};
    }
});



