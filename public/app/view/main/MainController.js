Ext.define('extJs.view.main.MainController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.main',

    onSampleItemSelected: function (sender, record) {
        var editWindow = Ext.create('Ext.window.Window', {
            title: 'Edit Item',
            modal: true,
            items: [{
                xtype: 'form',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Name',
                    name: 'name',
                    value: record.get('name')
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'Status',
                    name: 'status',
                    value: record.get('status')
                }, {
                    xtype: 'datefield',
                    fieldLabel: 'Date Last',
                    name: 'date',
                    format: 'Y-m-d H:i:s',
                    value: Ext.Date.parse(record.get('dateLast'), 'Y-m-d\\TH:i:sP')
                }]
            }],
            buttons: [{
                text: 'Save',
                handler: function () {
                    var form = editWindow.down('form');
                    if (form.isValid()) {
                        record.set(form.getValues());
                        Ext.Ajax.request({
                            url: '/sample/' + record.getData().id, 
                            method: 'PUT',
                            jsonData: record.getData(),
                            success: function (response) {
                                console.log(response);
                            },
                            failure: function (response) {
                                console.log(response);
                            }
                        });

                        editWindow.close();
                    }
                }
            }, {
                text: 'Cancel',
                handler: function () {
                    editWindow.close();
                }
            }]
        });

        editWindow.show();
    },

    onAddItemClick: function () {
        var newRecord = Ext.create('extJs.model.Sample', {
            id: '',
            name: '',
            status: '',
            dateLast: ''
        });

        var addWindow = Ext.create('Ext.window.Window', {
            title: 'Add Item',
            modal: true,
            items: [{
                xtype: 'form',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Name',
                    name: 'name',
                    value: newRecord.get('name')
                }, {
                    xtype: 'textfield',
                    fieldLabel: 'Status',
                    name: 'status',
                    value: newRecord.get('status')
                }, {
                    xtype: 'datefield',
                    fieldLabel: 'Date Last',
                    name: 'date',
                    format: 'Y-m-d H:i:s', 
                    value: newRecord.get('dateLast')
                }]
            }],
            buttons: [{
                text: 'Save',
                handler: function () {
                    var form = addWindow.down('form');
                    if (form.isValid()) {
                        newRecord.set(form.getValues());
                        addWindow.close();
                        Ext.Ajax.request({
                            url: '/sample',
                            method: 'POST',
                            jsonData: newRecord.getData(),
                            success: function (response) {
                                console.log(response);
                                Ext.create('Ext.window.Window', {
                                    title: 'Info',
                                    modal: false,
                                    closable: true,
                                    closeAction: 'destroy',
                                    items: {
                                        xtype: 'container',
                                        padding: 10,
                                        html: response.responseText
                                    },
                                
                                    buttons: [
                                        {
                                            text: 'OK',
                                            handler: function () {
                                                this.up('window').close();
                                                window.location.reload();
                                            }
                                        }
                                    ]
                                }).show();
                            },
                            failure: function (response) {
                                console.log(response);
                                Ext.create('Ext.window.Window', {
                                    title: 'Info',
                                    modal: false,
                                    closable: true,
                                    closeAction: 'destroy',
                                    items: {
                                        xtype: 'container',
                                        padding: 10,
                                        html: response.responseText
                                    },
                                
                                    buttons: [
                                        {
                                            text: 'OK',
                                            handler: function () {
                                                this.up('window').close();
                                            }
                                        }
                                    ]
                                }).show();
                            }
                        });
                    }
                }
            }, {
                text: 'Cancel',
                handler: function () {
                    addWindow.close();
                }
            }]
        });

        addWindow.show();
    }
});
