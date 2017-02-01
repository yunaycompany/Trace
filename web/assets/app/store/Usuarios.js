Ext.define('UCI.store.Usuarios', {
    extend: 'Ext.data.Store',

    requires: [
        'UCI.model.Usuario'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            //autoLoad: true,
           // autoSync: true,
            storeId: 'Usuarios',
            model: 'UCI.model.Usuario',           
             proxy: {
                type: 'ajax',
                api: {
                    read: Routing.generate('logtrace_usuario')
                },
                reader: {
                    type: 'json',
                    root: 'usuarios'                 
                }
            }
        }, cfg)]);
    }
});

