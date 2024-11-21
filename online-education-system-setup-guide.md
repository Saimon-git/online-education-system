
# **Guía de Configuración del Proyecto: Online Education System**

¡Bienvenido! Sigue estos pasos para configurar y ejecutar este proyecto satisfactoriamente en tu entorno local.

---

## **Requisitos Previos**

Asegúrate de tener instalados los siguientes programas:

- **PHP**: Versión 8.1 o superior
- **Composer**: Administrador de dependencias de PHP
- **Node.js**: Versión 16 o superior
- **NPM** o **Yarn**: Administrador de paquetes de JavaScript
- **MySQL** o **PostgreSQL**: Base de datos compatible con Laravel
- **Git**: Para clonar el repositorio

---

## **Instrucciones**

### **1. Clonar el Repositorio**
Primero, clona el proyecto desde el repositorio de GitHub:

```bash
git clone https://github.com/Saimon-git/online-education-system.git
cd online-education-system
```

---

### **2. Instalar Dependencias**
#### PHP
Instala las dependencias de PHP con Composer:

```bash
composer install
```

#### Node.js
Instala las dependencias de JavaScript:

```bash
npm install
```
o, si prefieres Yarn:

```bash
yarn install
```

---

### **3. Configurar el Archivo `.env`**
Crea el archivo de configuración `.env` copiando el ejemplo proporcionado:

```bash
cp .env.example .env
```

Edita el archivo `.env` para agregar la configuración de tu base de datos y otras variables de entorno:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

APP_URL=http://localhost
```

---

### **4. Generar la Clave de la Aplicación**
Genera la clave única de la aplicación:

```bash
php artisan key:generate
```

---

### **5. Ejecutar las Migraciones y Seeders**
Configura la base de datos ejecutando las migraciones y seeders:

```bash
php artisan migrate:fresh --seed
```

Esto creará las tablas necesarias y poblará la base de datos con datos iniciales.

---

### **6. Compilar los Archivos Frontend**
Compila los archivos CSS y JavaScript:

#### Desarrollo:
```bash
npm run dev
```

#### Producción:
```bash
npm run build
```

---

### **7. Iniciar el Servidor**
Inicia el servidor local de Laravel:

```bash
php artisan serve
```

El proyecto estará disponible en [http://localhost:8000](http://localhost:8000).

---

## **Pruebas**

Para ejecutar las pruebas, usa el siguiente comando:

```bash
php artisan test
```

Esto verificará que las funcionalidades implementadas están funcionando correctamente.

---

## **Usuarios Iniciales**

El proyecto incluye usuarios predefinidos para fines de prueba:

- **Administrador:**
  - Email: `s.montoya@mail.test`
  - Contraseña: `password`

- **Usuario Regular:**
  - Email: `user@example.com`
  - Contraseña: `password`

---

## **Algunas Rutas Principales**

### **Frontend**
- `/dashboard`: Dashboard principal.
- `/courses`: Lista de cursos (user,admin).
- `/comments`: Gestión de comentarios (admin).

### **API**
- `GET /api/courses`: Lista de cursos con paginación.
- `POST /api/courses/{id}/videos`: Asociar videos a un curso.
- `POST /api/comments/{id}/approve`: Aprobar comentario.
- `POST /api/comments/{id}/decline`: Declinar comentario.

---

## **Problemas Comunes**

### **1. Error de Permisos**
Si encuentras errores de permisos al trabajar con Laravel o el almacenamiento:

```bash
chmod -R 775 storage bootstrap/cache
```

### **2. Base de Datos no Conectada**
Verifica que las credenciales de la base de datos en `.env` sean correctas.

---

¡Listo! Ahora deberías tener el proyecto funcionando correctamente. Si tienes algún problema, no dudes en crear un issue en el repositorio o contactar con el administrador del proyecto. 😊
