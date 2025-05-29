# Aplicación de Pedidos para una Tienda de Accesorios

## Portada
- **Nombre del proyecto**: Sistema de Gestión de Pedidos
- **Autor**: Laura Nievas Coca
- **Fecha**: 17/02/2025
- **Versión**: v1.0
- **Descripción**: Aplicación web para gestionar pedidos, con funcionalidades como registro de usuarios, carrito de compras y seguimiento de pedidos.

---

## Índice
- [Aplicación de Pedidos para una Tienda de Accesorios](#aplicación-de-pedidos-para-una-tienda-de-accesorios)
  - [Portada](#portada)
  - [Índice](#índice)
  - [Introducción](#introducción)
  - [Estructura del proyecto](#estructura-del-proyecto)
  - [Explicación de los ficheros](#explicación-de-los-ficheros)
  - [Conclusión](#conclusión)

## Introducción
Este proyecto es una aplicación web desarrollada en PHP para gestionar pedidos. Permite a los usuarios registrarse, agregar productos a un carrito de compras, realizar pedidos y ver el estado de los mismos. La aplicación sigue una arquitectura MVC (Modelo-Vista-Controlador) para mantener un código organizado y escalable.

---

## Estructura del proyecto
La estructura del proyecto es la siguiente:
/app
├── /assets
│   ├── /css
│   │   └── estilos.css      # Archivo CSS para los estilos de la aplicación
│   └── /img                 # Directorio para almacenar imágenes estáticas (logos, iconos, etc.)
│
├── /config
│   ├── db.php              # Configuraciones generales de la aplicación
│   └── parameters.php      # Parámetros adicionales de configuración
│
├── /controllers
│   ├── CarritoController.php  # Controlador para gestionar el carrito de compras
│   ├── CategoriaController.php # Controlador para gestionar categorías de productos
│   ├── ErrorController.php     # Controlador para manejar errores
│   ├── PedidoController.php    # Controlador para gestionar pedidos
│   ├── ProductoController.php  # Controlador para gestionar productos
│   └── UsuarioController.php   # Controlador para gestionar usuarios
│
├── tienda.sql        # Script SQL para crear la base de datos y tablas
│ 
│   
├── /helpers
│   ├── utils.php                 # Funciones auxiliares y utilidades generales
│   └── mailHelpers.php           # Utilidad para enviar correos electrónicos usando PHPMailer
│
├── /uploads        # Directorio para almacenar imágenes subidas por los usuarios
│
├── /lib                    # Biblioteca de clases o funciones adicionales
│   └── database.php        # Configuración y conexión a la base de datos    
├── /models
│   ├── categoria.php       # Modelo para gestionar categorías de productos
│   ├── pedido.php          # Modelo para gestionar pedidos
│   ├── producto.php        # Modelo para gestionar productos
│   └── usuario.php         # Modelo para gestionar usuarios
│
├── /vendor                 # Dependencias de Composer (autogenerado)
│
├── /views
│   ├── /carrito
│   │   └── index.php       # Vista para mostrar el carrito de compras
│   ├── /categoria
│   │   ├── create.php      # Vista para crear una nueva categoría
│   │   ├── index.php       # Vista para listar categorías
│   │   └── ver.php         # Vista para ver detalles de una categoría
│   ├── /layout
│   │   ├── footer.php      # Pie de página común para todas las vistas
│   │   ├── header.php      # Cabecera común para todas las vistas
│   │   └── sidebar.php     # Barra lateral común para todas las vistas
│   ├── /pedido
│   │   ├── confirmado.php  # Vista para confirmar un pedido realizado
│   │   ├── detalle.php     # Vista para ver detalles de un pedido
│   │   ├── hacer.php       # Vista para realizar un nuevo pedido
│   │   └── misPedidos.php  # Vista para listar los pedidos del usuario
│   ├── /producto
│   │   ├── create.php      # Vista para crear un nuevo producto
│   │   ├── destacados.php  # Vista para mostrar productos destacados
│   │   ├── gestion.php     # Vista para gestionar productos (CRUD)
│   │   └── ver.php         # Vista para ver detalles de un producto
│   └── /usuario
│       ├── formregistro.php    # Vista para el formulario de registro de usuarios
│       ├── editar.php          # Vista para editar los datos de un usuario existente
│       ├── gestion.php         # Vista para gestionar usuarios (lista de usuarios)
│       └── modificar.php        # Vista para modificar datos sensibles, como la contraseña
│
├── .env                    # Archivos de entorno (configuraciones sensibles)
│
├── .gitignore              # Archivo para ignorar archivos en Git
│
├── .htaccess               # Configuración del servidor Apache
│
├── autoload.php            # Autoloader para cargar clases automáticamente
│
├── composer.json           # Configuración de Composer y dependencias
│
├── composer.lock           # Versiones bloqueadas de las dependencias
│
├── documentacion.md        # Documentación del proyecto
│
├── index.php               # Punto de entrada principal de la aplicación
│
└── indexProyecto.php       # Archivo adicional de entrada (opcional o alternativo)

---

## Explicación de los ficheros
- **Ficheros principales**
  - index.php: Punto de entrada principal de la aplicación. Se encarga de cargar las dependencias y redirigir las        solicitudes a los controladores correspondientes.

  - indexProyecto.php: Archivo adicional de entrada (opcional o alternativo).

  - .htaccess: Configuración del servidor Apache para redireccionar todas las solicitudes a index.php y habilitar URLs amigables.

  - composer.json: Archivo de configuración de Composer, utilizado para gestionar dependencias y autoloading.

  - composer.lock: Versiones bloqueadas de las dependencias instaladas por Composer.

- **Ficheros de configuración**
  - config.php: Contiene configuraciones generales de la aplicación, como la URL base y constantes globales.

  - parametros.php: Define parámetros adicionales de configuración, como claves de API o ajustes específicos del servidor.

- **Ficheros de modelos**
  - categoria.php: Modelo que interactúa con la tabla de categorías en la base de datos.

  - pedido.php: Modelo que gestiona las operaciones relacionadas con los pedidos.

  - producto.php: Modelo que maneja la lógica relacionada con los productos.

  - usuario.php: Modelo que gestiona las operaciones relacionadas con los usuarios.

- **Ficheros de controladores**
  - CarritoController.php: Gestiona la lógica relacionada con el carrito de compras, como agregar, eliminar o actualizar productos.

  - CategoriaController.php: Maneja las operaciones relacionadas con las categorías de productos, como listar o filtrar productos por categoría.

  - ErrorController.php: Controla la visualización de páginas de error personalizadas (por ejemplo, error 404).

  - PedidoController.php: Gestiona la creación, consulta y actualización de pedidos.

  - ProductoController.php: Maneja la lógica relacionada con los productos, como mostrar detalles, agregar nuevos productos o actualizarlos.

  - UsuarioController.php: Controla las acciones relacionadas con los usuarios, como registro, inicio de sesión y gestión de perfiles.

- **Ficheros de vistas**
  - /carrito/index.php: Muestra el contenido del carrito de compras.

  - /categoria/create.php: Formulario para crear una nueva categoría.

  - /categoria/index.php: Lista todas las categorías disponibles.

  - /categoria/ver.php: Muestra los detalles de una categoría específica.

  - /layout/footer.php: Pie de página común para todas las vistas.

  - /layout/header.php: Cabecera común para todas las vistas.

  - /layout/sidebar.php: Barra lateral común para todas las vistas.

  - /pedido/confirmado.php: Confirma que un pedido ha sido realizado.

  - /pedido/detalle.php: Muestra los detalles de un pedido específico.

  - /pedido/hacer.php: Formulario para realizar un nuevo pedido.

  - /pedido/misPedidos.php: Lista los pedidos realizados por el usuario.

  - /producto/create.php: Formulario para crear un nuevo producto.

  - /producto/destacados.php: Muestra productos destacados.

  - /producto/gestion.php: Permite gestionar productos (CRUD).

  - /producto/ver.php: Muestra los detalles de un producto específico.

  - /usuario/formregistro.php: Formulario de registro de usuarios.

  - /usuario/editar.php: Formulario para editar los datos de un usuario existente.

  - /usuario/gestion.php: Lista de usuarios para administradores, con opciones de gestión.

  - /usuario/modificar.php: Formulario para modificar datos sensibles, como la contraseña.

- **Ficheros de utilidades**
  - utilidades.php: Funciones auxiliares que se utilizan en diferentes partes de la aplicación, como validación de datos, formateo de fechas o cálculos.

  - email.php: Utiliza la librería PHPMailer para enviar correos electrónicos desde la aplicación.
   
  - autoload.php: Autoloader para cargar clases automáticamente.

- **Otros ficheros**
  - .gitignore: Especifica archivos y directorios que deben ser ignorados por Git.

  - documentacion.md: Documentación del proyecto.

  - /assets/css/estilos.css: Archivo CSS que define los estilos visuales de la aplicación.

  - .env: Directorio para archivos de entorno (configuraciones sensibles).

---

## Conclusión
Este proyecto es una aplicación web completa para gestionar pedidos en una tienda de accesorios, desarrollada con PHP y una arquitectura MVC (Modelo-Vista-Controlador). La estructura del proyecto está diseñada para ser modular y fácil de mantener, lo que permite la escalabilidad y la adición de nuevas funcionalidades en el futuro. 

La separación clara entre controladores, modelos y vistas facilita la organización del código y su mantenimiento. Además, el uso de herramientas como Composer para gestionar dependencias y un archivo `.htaccess` para configurar el servidor Apache mejora la eficiencia y la seguridad de la aplicación.

Para más detalles, consulta el código fuente o la documentación adicional. Este proyecto es una base sólida para cualquier sistema de gestión de pedidos y puede adaptarse a las necesidades específicas de cualquier negocio.