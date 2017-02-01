Ext.define('UCI.store.Acchoras', {
    extend: 'Ext.data.Store',
    requires: [
        'UCI.model.AccHora'
    ],
    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'accionesidhora',
            model: 'UCI.model.AccHora',
            proxy: {
                type: 'ajax',
                api: {
                    read: Routing.generate('logtrace_allhoraacc')
                },
                reader: {
                    type: 'json',
                    root: 'datos'
                }
            }

        }, cfg)]);
    }
});

