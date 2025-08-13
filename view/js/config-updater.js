/**
 * =================================================================
 * Gestor de Configuración Global (APP_CONFIG)
 * =================================================================
 * Este script crea un objeto global 'window.APP_CONFIG' para
 * manejar variables de configuración del sistema (Tasa BCV, IVA, etc.).
 *
 * FUNCIONAMIENTO:
 * 1. init(): Se ejecuta al cargar. Lee las variables iniciales
 *    del div #config-vars y las almacena.
 * 2. get(key): Permite a otros scripts obtener un valor de configuración
 *    actualizado (ej: APP_CONFIG.get('tasa_bcv')).
 * 3. update(newConfig): Recibe un objeto con nuevos valores, los fusiona
 *    con la configuración existente y dispara un evento global
 *    'configUpdated' para notificar a toda la aplicación del cambio.
 * 4. Otros scripts (productos.js, ventas.js, etc.) deben escuchar
 *    este evento para reaccionar a los cambios en tiempo real.
 */
(function(window, document) {
    'use strict';

    // 1. Definimos el objeto global
    const APP_CONFIG = {
        config: {},

        // 2. Inicializa la configuración desde el HTML
        init: function() {
            const configElement = document.getElementById('config-vars');
            if (configElement) {
                this.config = {
                    tasa_bcv: parseFloat(configElement.dataset.tasaBcv) || 0,
                    iva_porcentaje: parseFloat(configElement.dataset.ivaPorcentaje) || 16,
                    moneda_principal: configElement.dataset.monedaPrincipal || 'USD'
                };
                console.log('APP_CONFIG inicializado:', this.config);
            } else {
                console.error('El div #config-vars no fue encontrado. La configuración no se pudo cargar.');
            }
        },

        // 3. Obtiene un valor de la configuración
        get: function(key) {
            return this.config[key];
        },

        // 4. Actualiza la configuración y notifica a la aplicación
        update: function(newConfig) {
            // Fusiona la nueva configuración con la existente
            this.config = Object.assign({}, this.config, newConfig);
            
            console.log('APP_CONFIG actualizado:', this.config);

            // Dispara un evento global para que otros scripts se enteren
            const event = new CustomEvent('configUpdated', {
                detail: {
                    newConfig: this.config
                }
            });
            document.dispatchEvent(event);
            
            // También actualizamos el div #config-vars por si algún script lo lee directamente
            const configElement = document.getElementById('config-vars');
            if (configElement) {
                configElement.dataset.tasaBcv = this.config.tasa_bcv;
                configElement.dataset.ivaPorcentaje = this.config.iva_porcentaje;
            }
        }
    };

    // 5. Expone el objeto al scope global y lo inicializa
    window.APP_CONFIG = APP_CONFIG;
    document.addEventListener('DOMContentLoaded', function() {
        window.APP_CONFIG.init();
    });

})(window, document);