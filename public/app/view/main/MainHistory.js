Ext.define('extJs.view.main.MainHistory', {
    extend: 'Ext.grid.Panel',
    xtype: 'mainhistory',

    requires: [
        'extJs.store.History'
    ],

    title: 'History',

    store: {
        type: 'history'
    },

    columns: [
        { text: 'ID',  dataIndex: 'id' },
        { text: 'DocID', dataIndex: 'sampleId', flex: 1 },
        { text: 'Status', dataIndex: 'status', flex: 1 },
        { text: 'Date', dataIndex: 'date', flex: 1 }
    ],
});