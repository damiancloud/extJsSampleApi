Ext.define('extJs.store.Sample', {
    extend: 'Ext.data.Store',

    alias: 'store.sample',

    model: 'extJs.model.Sample',

    proxy: {
        type: 'ajax',
        url: '/sample',
        reader: {
            type: 'json',
            rootProperty: ''
        }
    },

    autoLoad: true 
});