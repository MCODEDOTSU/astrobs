/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    $('ul[rel*=treeview]').treeview({collapsed: true, persist: "cookie",unique: true});
});
