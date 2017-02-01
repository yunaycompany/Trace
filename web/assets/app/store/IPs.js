Ext.define('UCI.store.IPs', {
    extend: 'Ext.data.Store',

    requires: [
        'UCI.model.IP'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'ips',
            model: 'UCI.model.IP',
            proxy: {
                type: 'ajax',
                api: {
                    read: Routing.generate('logtrace_ip')
                },
                reader: {
                    type: 'json',
                    root: 'datos'
                }
            }
        }, cfg)]);
    }
});

