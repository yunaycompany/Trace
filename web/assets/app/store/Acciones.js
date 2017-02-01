Ext.define('UCI.store.Acciones', {
    extend: 'Ext.data.Store',

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'accionesid',
            fields:['accion'],
            data: [
                {'accion':'Create'},
                {'accion':'Update'},
                {'accion':'Delete'},
                {'accion':'Autentication'}
            ]
        }, cfg)]);
    }
});
