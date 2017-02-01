Ext.define('UCI.store.AllTrazas', {
    extend: 'Ext.data.Store',
    requires: [
        'UCI.model.AllTraza'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'AllTrazas',
            model: 'UCI.model.AllTraza',
            proxy: {
                type: 'ajax',
                api: {
                    read: Routing.generate('logtrace_alltraza')
                },
                reader: {
                    type: 'json',
                    root: 'datos'
                }
            }
        }, cfg)]);
    }
});
