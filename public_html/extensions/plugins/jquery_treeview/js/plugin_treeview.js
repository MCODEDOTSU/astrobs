/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    //$('body').ajaxComplete(function(){
        $('ul[rel*=treeview]').treeview({collapsed: true, persist: "cookie"});                    
    //});
    //$('ul[rel*=treeview]').treeview({collapsed: true, persist: "cookie", unique: true});                    
});
