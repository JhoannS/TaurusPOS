module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            
            colors: {
                mono: {
                  blanco: '#F5EFFF',
                  negro: '#060809',
                },
                bg: {
                  empty: '#1F1D2B',
                },
                universal:{
                  naranja: '#FE4F2D',
                  azul: '#4F959D',
                },
                semaforo: {
                  verde: '#1BCC75',
                  verde_opacity: '#1bcc763f',
                  amarillo: '#FFA823',
                  rojo: '#FF3131',
                  rojo_opacity : '#ff31312d'
                },
                secundary: {
                  default: '#1A2130',
                  light: '#A5B8D4',
                  opacity: 'rgba(26, 33, 48, 0.479)'
                },
                machine: {
                  primary: '#57CC99',
                  secundary: '#C7F9CC',
                  complement: '#80ED99'
                },
                guru: {
                  primary: '#BB9457',
                  secundary: '#FFE6A7',
                  complement: '#99582A'
                },
                shopper: {
                  primary: '#3D4AEE',
                  secundary: '#87A2FF',
                  complement: '#8E9AAF'
                },
                essentials: {
                  primary: '#FF4D6D',
                  secundary: '#FFB3C1',
                  complement: '#FF758F'
                },
                smart: {
                  primary: '#DEE2FF',
                  secundary: '#FEEAFA',
                  complement: '#EFD3D7'
                },
            },
            boxShadow: {
              'universal-naranja': '0px 8px 28px #FE4F2D;',
              'essentials': '0 8px 28px rgba(79, 70, 229, 0.5)',
              'machine': '0 4px 6px rgba(22, 163, 74, 0.5)',
              'shopper': '0 4px 6px rgba(239, 68, 68, 0.5)'
            }
        },
    },
    plugins: [],
  }
  