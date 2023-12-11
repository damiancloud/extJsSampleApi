Ext.define('extJs.view.main.List', {
    extend: 'Ext.grid.Panel',
    xtype: 'mainlist',

    requires: [
        'extJs.store.Sample'
    ],

    title: 'Sample',

    store: {
        type: 'sample'
    },

    columns: [
        { text: 'ID',  dataIndex: 'id' },
        { text: 'Name', dataIndex: 'name', flex: 1 },
        { 
            text: 'DateCreated', 
            dataIndex: 'dateCreated', 
            flex: 1,
            renderer: function(value) {
                var parsedDate = Ext.Date.parse(value, "Y-m-d\\TH:i:sP");
                return Ext.Date.format(parsedDate, 'Y-m-d H:i:s');
            }
        },
        { text: 'Status', dataIndex: 'status', flex: 1 },
        { 
            text: 'DateLast', 
            dataIndex: 'dateLast', 
            flex: 1,
            renderer: function(value) {
                var parsedDate = Ext.Date.parse(value, "Y-m-d\\TH:i:sP");
                return Ext.Date.format(parsedDate, 'Y-m-d H:i:s');
            }
        }
    ],

    listeners: {
        select: 'onSampleItemSelected'
    },
    tbar: [
    {
        text: 'Add Item',
        handler: 'onAddItemClick'
    }
    ],
});
