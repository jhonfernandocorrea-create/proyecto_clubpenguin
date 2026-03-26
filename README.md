# 🐾 Club penguin - Sistema de Ventas de Productos para Mascotas

Este es un proyecto de comercio electrónico completo diseñado para la venta de productos para mascotas. El sistema permite gestionar un catálogo de productos, carrito de compras y administración de inventario.

---

## 🚀 Tecnologías Utilizadas

El proyecto fue desarrollado utilizando un stack clásico de desarrollo web:

*   **Frontend:**
    *   HTML5: Estructura de la página.
    *   CSS3: Diseño responsivo y estilos personalizados.
    *   JavaScript (Vanilla): Manejo del carrito de compras, validaciones y efectos visuales.
*   **Backend:**
    *   PHP 8.x: Lógica del servidor y conexión con la base de datos.
*   **Base de Datos:**
    *   MySQL: Almacenamiento de productos, usuarios y pedidos.
*   **Servidor Local:**
    *   XAMPP (Apache).

---

## 🛠️ Requisitos e Instalación

Para ejecutar este proyecto localmente, sigue estos pasos:

### 1. Preparar el entorno
*   Descarga e instala [XAMPP]
*   Asegúrate de que los módulos **Apache** y **MySQL** estén activos en el Panel de Control de XAMPP.

### 2. Configurar la Base de Datos
1.  Accede a 
2.  Crea una nueva base de datos llamada 
3.  Importa el archivo `.sql` que se encuentra en la carpeta `/db/` de este repositorio.

### 3. Desplegar el código
1.  Copia la carpeta del proyecto.
2.  Pégala en el directorio de tu servidor local
3.  Abre tu navegador 

---

## 📂 Estructura del Proyecto

```text
petshop/
├── assets/           # Imágenes de productos, logos e iconos.
├── css/              # Hojas de estilo (style.css).
├── js/               # Scripts de interacción y carrito.
├── includes/         # Conexión a BD y componentes PHP reutilizables (header/footer).
├── admin/            # Panel de gestión para el administrador.
├── db/               # Script SQL de la base de datos.
├── index.php         # Página principal de la tienda.
└── productos.php     # Catálogo dinámico de productos.
