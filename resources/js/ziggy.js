const Ziggy = {
    url: "http://localhost",
    port: null,
    defaults: {},
    routes: {
        "sanctum.csrf-cookie": {
            uri: "sanctum/csrf-cookie",
            methods: ["GET", "HEAD"],
        },
        "essentials.admin.crearItem": {
            uri: "essentials/admin/crearItem",
            methods: ["GET", "HEAD"],
        },
        "aplicacion.dashboard": {
            uri: "{aplicacion}/{rol}/dashboard",
            methods: ["GET", "HEAD"],
            parameters: ["aplicacion", "rol"],
        },
        "aplicacion.configuraciones": {
            uri: "{aplicacion}/{rol}/configuraciones",
            methods: ["GET", "HEAD"],
            parameters: ["aplicacion", "rol"],
        },
        "aplicacion.usuarios": {
            uri: "{aplicacion}/{rol}/usuarios",
            methods: ["GET", "HEAD"],
            parameters: ["aplicacion", "rol"],
        },
        "aplicacion.reportes": {
            uri: "{aplicacion}/{rol}/reportes",
            methods: ["GET", "HEAD"],
            parameters: ["aplicacion", "rol"],
        },
        "essentials.admin.etiquetado": {
            uri: "essentials/admin/etiquetado",
            methods: ["GET", "HEAD"],
        },
        "essentials.admin.gastos": {
            uri: "essentials/admin/gastos",
            methods: ["GET", "HEAD"],
        },
        "essentials.admin.multisucursales": {
            uri: "essentials/admin/multisucursales",
            methods: ["GET", "HEAD"],
        },
        "essentials.admin.infoSucursal": {
            uri: "essentials/admin/infoSucursal",
            methods: ["GET", "HEAD"],
        },
        "essentials.admin.generadorQrs": {
            uri: "essentials/admin/generadorQrs",
            methods: ["GET", "HEAD"],
        },
        "essentials.cliente.dashboard": {
            uri: "essentials/cliente/dashboard",
            methods: ["GET", "HEAD"],
        },
        swap: { uri: "swap", methods: ["GET", "HEAD"] },
        "essentials.empleado.dashboard": {
            uri: "essentials/empleado/dashboard",
            methods: ["GET", "HEAD"],
        },
        "guru.admin.dashboard": {
            uri: "guru/admin/dashboard",
            methods: ["GET", "HEAD"],
        },
        "cliente.dashboard": {
            uri: "smart/cliente/dashboard",
            methods: ["GET", "HEAD"],
        },
        "empleado.dashboard": {
            uri: "smart/empleado/dashboard",
            methods: ["GET", "HEAD"],
        },
        "admin.dashboard": {
            uri: "smart/admin/dashboard",
            methods: ["GET", "HEAD"],
        },
        "superAdmin.dashboard": {
            uri: "TaurusCO/superAdmin/dashboard",

            methods: ["GET", "HEAD"],
        },
        "login.auth": {
            uri: "login/auth",
            methods: ["GET", "HEAD"],
        },
        "login.post": {
            uri: "login/auth",
            methods: ["POST"],
        },
        logout: {
            uri: "login/logout",
            methods: ["POST"],
        },
        "register.auth": {
            uri: "register/auth",
            methods: ["GET", "HEAD"],
        },
        register: {
            uri: "register/auth",
            methods: ["POST"],
        },
        "home.index": {
            uri: "/",
            methods: ["GET", "HEAD"],
        },
        "storage.local": {
            uri: "storage/{path}",
            methods: ["GET", "HEAD"],
            wheres: {
                path: ".*",
            },
            parameters: ["path"],
        },
    },
};
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
cde;
