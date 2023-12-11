Ext.define('extJs.store.History', {
    extend: 'Ext.data.Store',

    alias: 'store.history',

    model: 'extJs.model.History',

    proxy: {
        type: 'ajax',
        url: '/history',
        reader: {
            type: 'json',
            rootProperty: ''
        }
    },

    autoLoad: true 
});