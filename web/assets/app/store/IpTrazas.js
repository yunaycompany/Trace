Ext.define('UCI.store.IpTrazas', {
    extend: 'Ext.data.Store',

    requires: [
        'UCI.model.IpTraza'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'IpTrazas',
            model: 'UCI.model.IpTraza',
            proxy: {
                type: 'ajax',
                api: {
                    read: Routing.generate('logtrace_trazaip')
                },
                reader: {
                    type: 'json',
                    root: 'datos'
                }
            }
        }, cfg)]);
    }
});