Ext.define('UCI.store.UGraficos', {
    extend: 'Ext.data.Store',

    requires: [
        'UCI.model.UGrafico'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'UGraficos',
            model: 'UCI.model.UGrafico',
            proxy: {
                type: 'ajax',
                api: {
                 //   read: Routing.generate('logtrace_gusuario')
                },
                reader: {
                    type: 'json',
                    root: 'datos'                   
                }
            }
        }, cfg)]);
    }
});
