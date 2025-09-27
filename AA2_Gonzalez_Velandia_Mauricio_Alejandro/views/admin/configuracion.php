<?php
$page_title = 'Configuración del Sistema';

include __DIR__ . '/layout.php';
?>

<style>
    .config-sections {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .config-section {
        background: #ffffff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e5e7;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out calc(0.05s * var(--i)) both;
    }

    .config-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .config-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .config-section:hover::before {
        opacity: 1;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        transition: transform 0.2s ease;
    }

    .config-section:hover .section-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .section-title {
        color: #1d1d1f;
        font-size: 1rem;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        background: #ffffff;
        color: #374151;
        transition: all 0.2s ease;
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .form-input:focus {
        outline: none;
        border-color: #007AFF;
        box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.1);
    }

    .form-textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: .3s;
        border-radius: 28px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #007AFF;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }

    .btn-save {
        background: #007AFF;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        cursor: pointer;
        position: relative;
        z-index: 1;
        font-size: 0.875rem;
    }

    .btn-save:hover {
        background: #0056CC;
        transform: translateY(-1px);
    }

    .btn-reset {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        cursor: pointer;
        position: relative;
        z-index: 1;
        font-size: 0.875rem;
    }

    .btn-reset:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .section-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .info-item {
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 6px;
        border: 1px solid #e5e5e7;
        text-align: center;
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .info-label {
        color: #6b7280;
        font-size: 0.625rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: #1d1d1f;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>

<div class="config-sections">
    <div class="config-section" style="--i: 1">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #007AFF, #34C759);">
                <i class="fas fa-store"></i>
            </div>
            <h3 class="section-title">Información de la Tienda</h3>
        </div>

        <div class="form-group">
            <label class="form-label">Nombre de la Tienda</label>
            <input type="text" class="form-input" value="Tienda Online González" placeholder="Ingresa el nombre de la tienda">
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea class="form-input form-textarea" placeholder="Descripción de la tienda">Tienda especializada en productos electrónicos y tecnología de última generación.</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Email de Contacto</label>
            <input type="email" class="form-input" value="contacto@tienda.com" placeholder="Email de contacto">
        </div>

        <div class="form-group">
            <label class="form-label">Teléfono</label>
            <input type="tel" class="form-input" value="+57 300 123 4567" placeholder="Número de teléfono">
        </div>

        <div class="section-actions">
            <button class="btn-save">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <button class="btn-reset">
                <i class="fas fa-undo"></i> Restablecer
            </button>
        </div>
    </div>

    <div class="config-section" style="--i: 2">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #FF9500, #FF3B30);">
                <i class="fas fa-cog"></i>
            </div>
            <h3 class="section-title">Configuración General</h3>
        </div>

        <div class="form-group">
            <label class="form-label">Moneda</label>
            <select class="form-input form-select">
                <option value="COP" selected>Peso Colombiano (COP)</option>
                <option value="USD">Dólar Americano (USD)</option>
                <option value="EUR">Euro (EUR)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Idioma</label>
            <select class="form-input form-select">
                <option value="es" selected>Español</option>
                <option value="en">English</option>
                <option value="pt">Português</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Zona Horaria</label>
            <select class="form-input form-select">
                <option value="America/Bogota" selected>Bogotá (GMT-5)</option>
                <option value="America/New_York">Nueva York (GMT-5)</option>
                <option value="Europe/Madrid">Madrid (GMT+1)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Mantenimiento</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
                <span style="color: rgba(255, 255, 255, 0.8);">Modo mantenimiento activado</span>
            </div>
        </div>

        <div class="section-actions">
            <button class="btn-save">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <button class="btn-reset">
                <i class="fas fa-undo"></i> Restablecer
            </button>
        </div>
    </div>

    <div class="config-section" style="--i: 3">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #34C759, #007AFF);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="section-title">Seguridad</h3>
        </div>

        <div class="form-group">
            <label class="form-label">Cambiar Contraseña</label>
            <input type="password" class="form-input" placeholder="Nueva contraseña">
        </div>

        <div class="form-group">
            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-input" placeholder="Confirmar nueva contraseña">
        </div>

        <div class="form-group">
            <label class="form-label">Sesiones Simultáneas</label>
            <select class="form-input form-select">
                <option value="1" selected>1 sesión</option>
                <option value="2">2 sesiones</option>
                <option value="3">3 sesiones</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Autenticación de Dos Factores</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
                <span style="color: rgba(255, 255, 255, 0.8);">2FA activado</span>
            </div>
        </div>

        <div class="section-actions">
            <button class="btn-save">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <button class="btn-reset">
                <i class="fas fa-undo"></i> Restablecer
            </button>
        </div>
    </div>

    <div class="config-section" style="--i: 4">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #5856D6, #8E8E93);">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h3 class="section-title">Estadísticas del Sistema</h3>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Versión</div>
                <div class="info-value">v2.1.0</div>
            </div>
            <div class="info-item">
                <div class="info-label">Última Actualización</div>
                <div class="info-value">20/02/2024</div>
            </div>
            <div class="info-item">
                <div class="info-label">Espacio Usado</div>
                <div class="info-value">2.4 GB</div>
            </div>
            <div class="info-item">
                <div class="info-label">Usuarios Activos</div>
                <div class="info-value">118</div>
            </div>
            <div class="info-item">
                <div class="info-label">Productos</div>
                <div class="info-value">45</div>
            </div>
            <div class="info-item">
                <div class="info-label">Pedidos Hoy</div>
                <div class="info-value">12</div>
            </div>
        </div>

        <div class="section-actions">
            <button class="btn-save">
                <i class="fas fa-download"></i> Exportar Datos
            </button>
            <button class="btn-reset">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.config-section');
        sections.forEach(section => {
            section.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            section.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        const toggleSwitches = document.querySelectorAll('.toggle-switch input');
        toggleSwitches.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const slider = this.nextElementSibling;
                if (this.checked) {
                    slider.style.backgroundColor = '#007AFF';
                } else {
                    slider.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
                }
            });
        });

        const saveButtons = document.querySelectorAll('.btn-save');
        saveButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Guardado';
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
                    }, 2000);
                }, 1500);
            });
        });
    });
</script>

<?php include __DIR__ . '/layout_end.php'; ?>