/*
 * This file launches the application by asking Ext JS to create
 * and launch() the Application class.
 */
Ext.application({
    extend: 'extJs.Application',

    name: 'extJs',

    requires: [
        // This will automatically load all classes in the extJs namespace
        // so that application classes do not need to require each other.
        'extJs.*'
    ],

    // The name of the initial view to create.
    mainView: 'extJs.view.main.Main'
});