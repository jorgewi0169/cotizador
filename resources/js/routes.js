import Vue from 'vue';
import Router from 'vue-router'

Vue.use(Router)

function verificarAcceso(to, from, next) {
    let authUser = JSON.parse(localStorage.getItem('authUser'));
    if (authUser) {
        let listRolPermisosByUsuario = JSON.parse(localStorage.getItem('listRolPermisosByUsuario'));
        if (listRolPermisosByUsuario.includes(to.name)) {
            next();
        } else {
            let listRolPermisosByUsuarioFilter = [];
            listRolPermisosByUsuario.map(function(x) {

                if (x.includes('index')) {
                    listRolPermisosByUsuarioFilter.push(x);
                }
            })
            if (to.name == 'inicio.index') {
                next({ name: listRolPermisosByUsuarioFilter[0] });
            } else {
                next(from.path)
            }
        }
    } else {
        next('/login')
    }
}

export const rutas = [

        {
            path: '/login',
            name: 'login',
            component: require('./components/modulos/authenticate/login').default,
            beforeEnter: (to, from, next) => {
                let authUser = JSON.parse(localStorage.getItem('authUser'));
                    if (authUser) {
                        next({ name: 'inicio.index' });
                    } else {
                        next();
                    }
             }
        },
        {   path: '/',
            name: 'inicio.index',
            component: require('./components/modulos/dashboard/index').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
             
         },


        {
            path: '/usuarios',
            name: 'usuario.index',
            component: require('./components/modulos/usuarios/usuarios').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },
        {
            path: '/roles',
            name: 'rol.index',
            component: require('./components/modulos/usuarios/roles').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             } ,

        },
        {
            path: '/permisos',
            name: 'permisos.index',
            component: require('./components/modulos/usuarios/permisos').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             },

        },

        {
            path: '/documentos',
            name: 'documento.index',
            component: require('./components/modulos/usuarios/documentos').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },

        {
            path: '/configuracion',
            name: 'configuracion.index',
            component: require('./components/modulos/configuracion/configuracion').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },

        {
            path: '/categorias',
            name: 'categoria.index',
            component: require('./components/modulos/autos/categoria').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },
        {
            path: '/marcas',
            name: 'marca.index',
            component: require('./components/modulos/autos/marca').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },

        {
            path: '/tipovehiculos',
            name: 'tipovehiculo.index',
            component: require('./components/modulos/autos/tipo_vehiculo').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },
        {
            path: '/tipousos',
            name: 'tipouso.index',
            component: require('./components/modulos/autos/tipo_uso').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },

        {
            path: '/modelos',
            name: 'modelo.index',
            component: require('./components/modulos/autos/modelo').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
             }
        },

        {
            path: '/clientes',
            name: 'cliente.index',
            component: require('./components/modulos/cotizacion/cliente').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },

        {
            path: '/seguros',
            name: 'seguro.index',
            component: require('./components/modulos/seguro/tipo_seguro').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },

        {
            path: '/coberturadeducibles',
            name: 'coberturadeducible.index',
            component: require('./components/modulos/seguro/cobertura_deducible').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },
        {
            path: '/clasificaciones',
            name: 'clasificacion.index',
            component: require('./components/modulos/autos/clasificacion').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },

        {
            path: '/cotizaciones',
            name: 'cotizacion.index',
            component: require('./components/modulos/cotizacion/cotizar').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },

        {
            path: '/pago',
            name: 'pagos.index',
            component: require('./components/modulos/usuarios/pagos').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next);
            }
        },

        {
            path: '*',
            component: require('./components/plantilla/404').default
        }

        

]

export default new Router({

    routes: rutas,
    mode: 'history'
})


//Permisos de cotizacion