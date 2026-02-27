<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_108 extends App_module_migration
{
    public function up()
    {
        // Perform database upgrade here
    }
    public function logChanged()
    {
        /*
        -------- 1.0.8 (April 21, 2024) --------
        NEW UPDATE
        - Custom menu: supports creating, editing, deleting, and grouping lists of sidebar, setup, and clients menus. Manage display permissions for menus and custom menu groups according to roles & users.
        - Support for creating search links for information across various data tables: Tasks, Projects, Customers,... and sharing them in the workflow. For example, sharing a link that displays all Tasks related to the keyword "custom menu".

        CHANGED
        - Display scroll bar when the quick access menu has too many items.
        */
    }
}
