/**
 * Created by wakhan on 2/28/2018.
 */
$(function () {

    // ------------------ Fix the Tree Menu -----------------------------------
    var checkElement = $('.menu-open .treeview-menu');
    if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
        //Get the parent menu
        var parent = checkElement.parents('ul').first();
        //Close all open menus within the parent
        var ul = parent.find('ul:visible').slideUp(500);
        //Remove the menu-open class from the parent
        ul.removeClass('menu-open');
        //Get the parent li
        var parent_li = checkElement.parent("li");

        //Open the target menu and add the menu-open class
        checkElement.slideDown(500, function () {
            //Add the class active to the parent li
            checkElement.addClass('menu-open');
            //parent.find('li.active').removeClass('active');
            parent_li.addClass('active');
            //Fix the layout in case the sidebar stretches over the height of the window
            //_this.layout.fix();
        });
    }
    // ------------------ end of tree menu --------------------------------------
});