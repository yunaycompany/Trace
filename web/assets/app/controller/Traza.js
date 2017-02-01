Ext.define('UCI.controller.Traza', {
    extend: 'Ext.app.Controller',
    models: [
        'Traza',
        'Usuario',
        'AllTraza',
        'IpTraza',
        'IP',
        'AccHora'
    ],
    stores: [
        'Trazas',
        'Usuarios',
        'AllTrazas',
        'IpTrazas',
        'Acciones',
        'IPs',
        'Acchoras'
    ],
    views: [
        'traza.listado',
        'traza.detalles',
        'traza.ventana',
        'traza.grafico',
        'traza.graficoip',
        'traza.graficoacch',
        'traza.log',
        'comun.opciones',
        'graficos.alltrazas',
        'graficos.alliptrazas',
        'graficos.allacchora'
    ],
    refs: [
        {
            ref: 'tabs',
            selector: 'viewport > #tabs'
        }
    ],
    init: function() {
        var me = this;
        me.control({
            'viewport > #nav': {
                itemclick: me.addTab
            },
            '#idClear':{
              click: me.resetear
            },
            '#idLogClear':{
                click: me.resetearLog
            },
            '#AccHoraClear':{
                click: me.resetearGraf
            },
            '#btnUsuarioHora':{
                click: me.grafAccionHora
            },
            '#cbUser': {
                beforerender: me.muestra
            },
            '#cbUserGrid': {
                beforerender: me.muestra
            },
            '#cbUserLog':{
                beforerender: me.muestra
            },
            '#cbIPLog':{
                beforerender: me.dameip
            },
            '#cbIPGrid':{
                beforerender: me.dameip
            },
            '#allUsers': {
                change: me.habilita
            },
            '#biGraf': {
                click: me.graficar
            },
            '#biipGraf': {
                click: me.graficarip
            },
            '#iptraza': {
                afterrender: me.cargar
            },
            '#allacch':{
                afterrender: me.accgraf
            },
            '#dpFechaF':{
                select: me.trazas
            },
            '#dpFecha':{
                select: me.changeFecha
            },
            '#dpLogFechaF':{
                select: me.trazasLog
            },
            '#dtAccFecha':{
                select: me.changeFechaAcc
            },
            '#dtAccFechaF':{
                select: me.accFechaAcc
            },
            '#dpLogFecha':{
                select: me.changeFechaLog
            },
            '#idbuscar':{
                click: me.buscar
            },
            '#idLogbuscar':{
                click: me.generarLog
            },
            'trazalist':{
                cellclick: me.showDatos
            },
            'trazalist':{
                afterrender: me.cargarGrid
            }
        });
    },
    cargarGrid: function(c, o){
        var dt = new Date();
        var dtinicial = new Date();
        dtinicial.setMonth(dtinicial.getMonth()-1);
        var list = Ext.getCmp('idListado');
        list.mask('Cargando Datos....');
        var ff = Ext.getCmp('idFechaF');
        var fi = Ext.getCmp('idFecha');
        fi.setValue(dtinicial)
        fi.setMaxValue(dt);
        var store = this.getTrazasStore();
        ff.setMaxValue(dt);
        ff.setValue(dt);
        store.load({
            params:{
                fechaini: dtinicial,
                fechafin: dt,
                page: 1,
                start: 0,
                limit: 20
            },
            callback:   function(records, operation, success){
                list.unmask();
            }
        });
    },
    showDatos:function(tbl, td, ci,record,tr,ri,e,eobj){

        if(ci==6){
            var rec = record.get('detalles');
            var accion = record.get('accion');
            var datos = record.data;
            var obj = Ext.JSON.decode(rec);
            var mensaje;

            switch (accion) {
                case 'Create':
                    mensaje = '<p>Se creó un objeto de la <b>clase: ' + obj.clase + '</b> con los siguientes datos: </p>';
                    Ext.Object.each(obj, function(key, value) {
                        if (key != 'clase') {
                            if(Ext.isObject(value[1])){
                                Ext.Object.each(value[1], function(n,v){
                                    if(n=='date'){
                                        var fecha1 = Ext.String.splitWords(v,' ');
                                        mensaje += '<pre><b>' + key + '</b>: ' + fecha1[0] + '</pre>';
                                    }
                                });
                            }else{
                                 mensaje += '<pre><b>' + key + '</b>: ' + value[1] + '</pre>';
                            }
                        }
                    });
                    break;

                case 'Update':
                    mensaje = '<p>Se modificó un objeto de la <b>clase: ' + obj.clase + '</b> con los siguientes datos: \n</p>';
                    Ext.Object.each(obj, function(key, value) {
                        if (key != 'clase') {
                            if (key != 'id') {
                                if(Ext.isObject(value[0]) && Ext.isObject(value[1]))
                                {
                                    Ext.Object.each(value[0], function(n,v){
                                        if(n=='date'){
                                            var fecha = Ext.String.splitWords(v,' ');
                                            mensaje += '<p><b>Campo: ' + key + '</b></p><pre>valor inicial: ' + fecha[0] + '</pre>';
                                        }
                                    });
                                    Ext.Object.each(value[1], function(n,v){
                                        if(n=='date'){
                                            var fecha1 = Ext.String.splitWords(v,' ');
                                            mensaje += '<pre>nuevo valor: ' + fecha1[0] + '</pre>';
                                        }
                                    });
                                }else{
                                    mensaje += '<p><b>Campo: ' + key + '</b></p><pre>valor inicial: ' + value[0] + '</pre><pre>nuevo valor: ' + value[1] + '</pre>';
                                }
                            }
                        }
                    });
                    break;
                case 'Delete':
                    mensaje = '<p>Se eliminó el objeto con <b>ID</b>: ' + obj.id + ' de la <b>clase: ' + obj.clase + '.</b></p>';
                    break;
                case 'Autentication':
                    if (obj.tipo === 'Success') {
                        mensaje = '<p>El usuario: <b>' + record.get('usuario') + '</b> se ha autenticado satisfactoriamente.</p>';
                    } else {
                        mensaje = '<p>Ha ocurrido un error en la autenticación: <b>' + obj.mensaje + '</b></p>';
                    }
            }
           //console.log(mensaje);
            var edit = Ext.create('UCI.view.traza.ventana', {
                items: {
                    xtype: 'trazadetalles',
                    html: mensaje
                }
            });
            edit.show();
        }
    },
    buscar:function(){
        var list = Ext.getCmp('idListado');
        list.mask('Cargando Datos....');
        var fi = Ext.getCmp('idFecha').getValue();
        var ff = Ext.getCmp('idFechaF').getValue();
        var user = Ext.getCmp('cbidgrid').getValue();
        var ip = Ext.getCmp('cbipgrid').getValue();
        var acciones = Ext.getCmp('cbAccionesGrid').getValue();
        var store = this.getTrazasStore();
        store.currentPage = 1;
        store.load({
            params:{
                usuario: user,
                fechaini: fi,
                fechafin: ff,
                ip:       ip,
                acc: acciones,
                page: 1,
                start: 0,
                limit: 20
            },
            callback:   function(records, operation, success){
                list.unmask();
            }
        });
    },
    generarLog:function(){
        var fi = Ext.getCmp('idLogFecha').getValue();
        var ff = Ext.getCmp('idLogFechaF').getValue();
        var user = Ext.getCmp('cbuserLog').getValue();
        var ip = Ext.getCmp('cbipLog').getValue();
        var acciones = Ext.getCmp('cbAccionesLog').getValue();
        var btn = Ext.getCmp('logbuscar');
        var q = btn.baseParams;
        q.acc= acciones;
        q.fechaf = ff;
        q.fechai = fi;
        q.ip = ip;
        q.user = user;
       btn.setParams(q);
    },
    changeFecha: function(c,x,t){
        var fi = Ext.getCmp('idFecha');
        var ff = Ext.getCmp('idFechaF');
        var dato = fi.getValue();
        ff.setMinValue(dato);
    },
    changeFechaAcc: function(c,x,t){
        var fi = Ext.getCmp('dtidAccFecha');
        var ff = Ext.getCmp('dtidAccFechaF');
        var dato = fi.getValue();
        ff.setMinValue(dato);
    },
    accFechaAcc: function(c,x,t){
        var fi = Ext.getCmp('dtidAccFecha');
        var ff = Ext.getCmp('dtidAccFechaF');
        var dato = ff.getValue();
        fi.setMaxValue(dato);
    },
    changeFechaLog: function(c,x,t){
        var fi = Ext.getCmp('idLogFecha');
        var ff = Ext.getCmp('idLogFechaF');
        var dato = fi.getValue();
        ff.setMinValue(dato);
    },
    trazas: function(c,x,t){
        var fi = Ext.getCmp('idFecha');
        var ff = Ext.getCmp('idFechaF');
        var dato = ff.getValue();
       fi.setMaxValue(dato);
    },
    trazasLog: function(c,x,t){
        var fi = Ext.getCmp('idLogFecha');
        var ff = Ext.getCmp('idLogFechaF');
        var dato = ff.getValue();
        fi.setMaxValue(dato);
    },
    dameip: function(combo, rec){
        var store = this.getIPsStore();
        combo.store = store;
        store.load();
    },
    resetear: function(btn, ev, obj){
        var list = Ext.getCmp('idListado');
        list.mask('Cargando Datos....');
        var usuario = Ext.getCmp('cbidgrid');
        var ip = Ext.getCmp('cbipgrid');
        var fi = Ext.getCmp('idFecha');
        var ff = Ext.getCmp('idFechaF');
        fi.setMaxValue(new Date());
        ff.setMaxValue(new Date());
        ff.setMinValue();
        fi.reset();
        ff.reset();
        Ext.getCmp('cbAccionesGrid').reset();
        usuario.reset();
        ip.reset();
        this.muestra(usuario);
        this.dameip(ip);
        var store = this.getTrazasStore();
        store.load(function(records, operation, success){
                list.unmask();
            }
        );
    },
    resetearLog: function(btn, ev, obj){
        var usuario = Ext.getCmp('cbuserLog');
        var ip = Ext.getCmp('cbipLog');
        var fi = Ext.getCmp('idLogFecha');
        var ff = Ext.getCmp('idLogFechaF');
        fi.setMaxValue(new Date());
        ff.setMaxValue(new Date());
        fi.reset();
        ff.reset();
        Ext.getCmp('cbAccionesLog').reset();
        usuario.reset();
        ip.reset();
        this.muestra(usuario);
        this.dameip(ip);
    },
    addTab: function(view, rec) {
        var tabs = this.getTabs();
        var id = rec.data.id;
        var cls = "UCI.view.traza." + id;
        var tab = tabs.child('#' + id);
        if (rec.data.leaf) {
            if (!tab) {

                tab = tabs.add(Ext.create(cls, {
                    itemId: id,
                    title: rec.get('text'),
                    closable: true
                }));
            }
        }
        tabs.setActiveTab(tab);
    },
    muestra: function(combo, rec) {
        var store = this.getUsuariosStore();
        combo.store = store;
        store.load();
    },
    cargar: function(comp, rec){
        var grafico = Ext.get('mgrafip');
        grafico.mask('Cargando Datos....');
        var storeIP = this.getIpTrazasStore();
        storeIP.load( function(records, operation, success) {
            grafico.unmask();
        });
    },
    accgraf: function(comp, rec){
        var grafico = Ext.get('idacch');
        var usuario = Ext.getCmp('cbidAccHora');
        var ip = Ext.getCmp('dtiAccHoraIp');
        this.muestra(usuario);
        this.dameip(ip);
        grafico.mask('Cargando Datos....');
        var storeAcc = this.getAcchorasStore();
        storeAcc.load( function(records, operation, success) {
            grafico.unmask();
        });
    },
    habilita: function(ck, nv, ov, obj) {
        var cb = Ext.getCmp('cbid');
        if (nv) {
            cb.setDisabled(true);
        } else {
            cb.setDisabled(false);
        }
    },
    graficar: function(btn, ev, obj) {
        var grafico = Ext.get('alltrac');
        grafico.mask('Cargando Datos....');
        var usuario = Ext.getCmp('cbid').getValue();
        var alluser = Ext.getCmp('ckauser').getValue();
        var storegraf = this.getAllTrazasStore();
        if(alluser)
        {
            storegraf.load( function(records, operation, success) {
                grafico.unmask();
            });
        }else{
            storegraf.load({
                params:{
                    usuario: usuario
                }
            ,
                callback:   function(records, operation, success){
                grafico.unmask();
            }});
        }
    },
    graficarip: function(btn, ev, obj) {
        var grafico = Ext.get('alliptrac');
        grafico.mask('Cargando Datos....');
        var storeIP = this.getIpTrazasStore();
        storeIP.load( function(records, operation, success) {
            grafico.unmask();
        });
    },
    resetearGraf: function(){
        var usuario = Ext.getCmp('cbidAccHora');
        usuario.reset();
        var ip = Ext.getCmp('dtiAccHoraIp');
        ip.reset();
        var fi = Ext.getCmp('dtidAccFecha');
        var ff = Ext.getCmp('dtidAccFechaF');
        fi.reset();
        ff.reset();
        fi.setMaxValue(new Date());
        ff.setMaxValue(new Date());
        ff.setMinValue();
        this.muestra(usuario);
        this.dameip(ip);
        var grafico = Ext.get('idacch');
        grafico.mask('Cargando Datos....');
        var storeAcc = this.getAcchorasStore();
        storeAcc.load( function(records, operation, success) {
            grafico.unmask();
        });
    },
    grafAccionHora: function(){
        var usuario = Ext.getCmp('cbidAccHora').getValue();
        var ip = Ext.getCmp('dtiAccHoraIp').getValue();
        var fi = Ext.getCmp('dtidAccFecha').getValue();
        var ff = Ext.getCmp('dtidAccFechaF').getValue();
        var grafico = Ext.get('idacch');
        grafico.mask('Cargando Datos....');
        var storeAcc = this.getAcchorasStore();
        storeAcc.load({
                params:{
                    user: usuario,
                    ip: ip,
                    fi: fi,
                    ff: ff
                }
            ,
            callback:  function(records, operation, success) {
            grafico.unmask();
        }});
    }
});
