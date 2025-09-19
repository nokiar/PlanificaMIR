# PlanificaMIR

Una aplicación web para la planificación y seguimiento del estudio del MIR (Médico Interno Residente). Permite organizar asignaturas, temas, vueltas de estudio y tareas en un calendario integrado.

## 🚀 Características

### 📚 Gestión de Asignaturas y Temas
- **Organización por asignaturas**: Crea y gestiona diferentes materias de estudio
- **Temas detallados**: Añade temas específicos con seguimiento granular
- **Estados de progreso**: Marca lectura y esquema completados
- **Sistema de importancia y rentabilidad**: Clasifica temas por prioridad (Muy alta, Alta, Media, Baja) con codificación visual por colores

### 🔄 Sistema de Vueltas Dinámico
- **Vueltas ilimitadas**: Añade tantas rondas de estudio como necesites
- **Comportamiento en cascada**: Al marcar una vuelta se activan automáticamente las anteriores
- **Gestión flexible**: Añade o elimina vueltas según evolucione tu planificación
- **Filtrado por vuelta**: Visualiza qué temas has completado en cada ronda

### 📝 Anotaciones Inteligentes
- **Editor modal**: Interfaz limpia para editar anotaciones extensas
- **Indicador visual**: Los temas con anotaciones muestran un botón "Ver" (azul), los sin anotaciones "Añadir" (blanco)
- **Persistencia automática**: Las anotaciones se guardan instantáneamente en la base de datos

### 📅 Calendario y Tareas
- **Vista mensual**: Navegación intuitiva por meses
- **Gestión de tareas**: Añade tareas específicas para fechas concretas
- **Drag & Drop**: Arrastra tareas entre días para reorganizar
- **Tareas del día**: Vista rápida de las tareas de la fecha seleccionada

### 🎯 Filtros Avanzados
- **Panel de filtros colapsible**: Oculta/muestra según necesites
- **Filtros combinables**: Por nombre, lectura, esquema, importancia, rentabilidad y vuelta
- **Filtrado en tiempo real**: Los resultados se actualizan instantáneamente
- **Filtro por vuelta**: Dropdown único para seleccionar vueltas 1..N

### 🔧 Características Técnicas
- **Verificación de conectividad**: Comprueba automáticamente la conexión con XAMPP al cargar
- **Modal de ayuda**: Instrucciones claras si Apache o MySQL no están iniciados
- **Interfaz responsive**: Funciona en dispositivos móviles y escritorio
- **Persistencia robusta**: Todos los cambios se guardan automáticamente en MySQL

## 🛠️ Instalación y Configuración

### Prerrequisitos
- **XAMPP** (Apache + MySQL + PHP)
- Navegador web moderno

### Paso 1: Configurar XAMPP
1. Descarga e instala [XAMPP](https://www.apachefriends.org/)
2. Abre el Panel de Control de XAMPP
3. Inicia **Apache** y **MySQL** (espera a que ambos indicadores estén en verde)

### Paso 2: Instalar la Aplicación
1. Clona o descarga este repositorio en `c:\xampp\htdocs\PLANIFICAMIR\`
2. Abre phpMyAdmin: http://localhost/phpmyadmin
3. Crea una nueva base de datos llamada `planificamir`
4. Importa el archivo `database.sql` para crear las tablas iniciales
5. **Importante**: Ejecuta también `database_migration_add_annotations.sql` para añadir la columna de anotaciones:
   ```sql
   ALTER TABLE topics ADD COLUMN annotations TEXT;
   ```

### Paso 3: Verificar la Instalación
1. Abre tu navegador y ve a: http://localhost/PLANIFICAMIR/
2. Si ves un modal de "Conexión a la base de datos no disponible":
   - Asegúrate de que Apache y MySQL estén iniciados en XAMPP
   - Pulsa "Reintentar" una vez que estén activos
3. La aplicación debería cargar mostrando el dashboard principal

## 📖 Guía de Uso

### Gestión de Asignaturas
1. **Añadir asignatura**: Pulsa "+" junto a "Asignaturas" en el dashboard
2. **Ver temas**: Haz clic en cualquier asignatura para ver sus temas
3. **Añadir tema**: Dentro de una asignatura, pulsa "Añadir Tema"

### Trabajar con Temas
- **Marcar lectura/esquema**: Usa los checkboxes correspondientes
- **Establecer importancia/rentabilidad**: Selecciona del dropdown (con colores)
- **Gestionar vueltas**: Marca las casillas V1, V2, etc. (comportamiento en cascada)
- **Añadir anotaciones**: Pulsa "Añadir" o "Ver" en la columna de anotaciones

### Sistema de Vueltas
- **Añadir vuelta**: Pulsa "Añadir Vuelta" para crear una nueva ronda
- **Eliminar vuelta**: Pulsa "Eliminar Vuelta" para quitar la última
- **Marcar progreso**: Al marcar V3, automáticamente se marcan V1 y V2
- **Desmarcar**: Al desmarcar V2, se desmarcan V2, V3, V4... hasta la última

### Filtros y Búsqueda
1. **Abrir filtros**: Pulsa "Mostrar Filtros" en la vista de temas
2. **Combinar filtros**: Usa múltiples filtros simultáneamente
3. **Filtro de vuelta**: Selecciona "1", "2", etc. para ver solo temas completados en esa vuelta
4. **Limpiar**: Pulsa "Limpiar" para resetear todos los filtros

### Calendario y Tareas
1. **Navegar**: Usa las flechas o "Hoy" para moverte por el calendario
2. **Seleccionar día**: Haz clic en cualquier fecha
3. **Añadir tarea**: Con un día seleccionado, pulsa "Añadir Tarea"
4. **Mover tarea**: Arrastra cualquier tarea a otro día
5. **Completar tarea**: Marca el checkbox junto a la tarea

## 🗂️ Estructura de la Base de Datos

### Tabla `subjects`
- `id` (INT, PK): Identificador único
- `name` (VARCHAR): Nombre de la asignatura

### Tabla `topics`
- `id` (INT, PK): Identificador único
- `subject_id` (INT, FK): Referencia a la asignatura
- `name` (VARCHAR): Nombre del tema
- `reading` (BOOLEAN): Estado de lectura
- `esquema` (BOOLEAN): Estado del esquema
- `importance` (ENUM): Muy alta, Alta, Media, Baja
- `rentability` (ENUM): Muy alta, Alta, Media, Baja
- `annotations` (TEXT): Anotaciones del tema

### Tabla `topic_rounds`
- `id` (INT, PK): Identificador único
- `topic_id` (INT, FK): Referencia al tema
- `round_number` (INT): Número de vuelta (1, 2, 3...)
- `completed` (BOOLEAN): Estado de completado

### Tabla `tasks`
- `id` (INT, PK): Identificador único
- `name` (VARCHAR): Nombre de la tarea
- `date` (DATE): Fecha asignada
- `completed` (BOOLEAN): Estado de completado

## 🎨 Códigos de Color

### Importancia y Rentabilidad
- **🟢 Muy alta**: Verde oscuro (fondo #16a34a, texto blanco)
- **🟢 Alta**: Verde claro (fondo #86efac, texto negro)
- **🟡 Media**: Amarillo (fondo #fef08a, texto negro)
- **🔴 Baja**: Rojo claro (fondo #fecaca, texto negro)

### Botones de Anotaciones
- **🔵 "Ver"**: Fondo azul (#3b82f6), indica que hay anotaciones
- **⚪ "Añadir"**: Fondo blanco con borde, indica que no hay anotaciones

## 🚨 Solución de Problemas

### "Conexión a la base de datos no disponible"
**Síntomas**: Modal de error al cargar la página
**Solución**:
1. Abre XAMPP Control Panel
2. Inicia Apache (botón "Start")
3. Inicia MySQL (botón "Start")
4. Espera a que ambos indicadores estén en verde
5. Pulsa "Reintentar" en el modal

### "Unexpected token '<' in JSON"
**Síntomas**: Error en la consola del navegador
**Causa**: La aplicación está siendo servida como archivo estático, no por Apache
**Solución**: Asegúrate de acceder via http://localhost/PLANIFICAMIR/ (no file://)

### Las anotaciones no se guardan
**Síntomas**: Los cambios en anotaciones no persisten
**Causa**: Falta la columna `annotations` en la tabla `topics`
**Solución**: Ejecuta en phpMyAdmin:
```sql
USE planificamir;
ALTER TABLE topics ADD COLUMN annotations TEXT;
```

### Los filtros no funcionan
**Síntomas**: Los filtros no afectan la lista de temas
**Causa**: JavaScript deshabilitado o error de sintaxis
**Solución**: 
1. Abre las herramientas de desarrollador (F12)
2. Revisa la consola por errores
3. Recarga la página (Ctrl+F5)

## 🔧 Arquitectura Técnica

### Frontend
- **HTML5** + **Vanilla JavaScript** (ES6+)
- **Tailwind CSS** (vía CDN) para estilos
- **SPA** (Single Page Application) con enrutado manual
- **Fetch API** para comunicación con el backend

### Backend
- **PHP** con **PDO** para acceso a base de datos
- **API REST** estilo RPC en endpoint único (`api.php`)
- **MySQL** como base de datos relacional
- **Apache** como servidor web

### Patrones de Diseño
- **Event Delegation** para manejo dinámico de eventos
- **Observer Pattern** en actualizaciones de estado
- **Repository Pattern** para acceso a datos
- **Modal Factory** para generación de diálogos

## 🤝 Contribuir

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Añade nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Si encuentras algún problema o tienes sugerencias:

1. **Revisa la sección de Solución de Problemas** arriba
2. **Verifica que XAMPP esté funcionando** correctamente
3. **Comprueba la consola del navegador** para errores JavaScript
4. **Asegúrate de que la base de datos** tenga todas las tablas y columnas necesarias

---

**¡Buena suerte con tu preparación del MIR! 🩺📚**

