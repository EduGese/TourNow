
# TOURNOW  - Plataforma de Reservas de Actividades Turísticas

## Descripción del Proyecto

TourNow es una aplicación web que busca revolucionar la forma en que las empresas turísticas promocionan y gestionan sus tours, y cómo los viajeros exploran y reservan actividades turísticas en las ciudades de Madrid, Barcelona y Sevilla. La plataforma brinda a las empresas la oportunidad de crear perfiles, describir en detalle sus tours, especificar fechas, ubicaciones y precios, y exhibir imágenes atractivas para captar la atención de los clientes potenciales. Por otro lado, los usuarios pueden explorar una variedad de actividades, visualizar detalles importantes de cada tour y realizar reservas de manera conveniente y personalizada.

![TourNow](https://github.com/EduGese/TourNow/assets/122921699/ba563f48-ab8d-4ce4-80db-c11fa36087d9)



## Características Principales

#### Regístrate y Explora
Los usuarios pueden registrarse en la plataforma y explorar una amplia gama de tours disponibles en las ciudades seleccionadas.

#### Detalles de Tours
Los usuarios pueden ver detalles completos de cada tour, incluyendo ubicaciones, horarios, precios, descripciones y plazas disponibles.

#### Reserva Personalizada
La aplicación permite a los usuarios personalizar sus reservas según sus preferencias, eligiendo el número de tickets, fechas y opciones específicas.

#### Opiniones y Puntuaciones
Los usuarios pueden acceder a reseñas y puntuaciones de otros usuarios que han participado en los tours, lo que les ayuda a tomar decisiones informadas.

#### Seguimiento de Reservas
Los usuarios pueden realizar un seguimiento de las actividades reservadas, accediendo a una lista personalizada y recibiendo actualizaciones sobre cambios o recordatorios.

#### Perfiles Empresariales
Las empresas turísticas pueden crear perfiles y descripciones detalladas de sus tours, así como ajustar la disponibilidad y los precios.

#### Gestión Centralizada
Las empresas pueden gestionar de manera centralizada sus tours, crear nuevos, actualizar información y realizar un seguimiento de las reservas.

#### Análisis de Datos
La plataforma recopila datos relevantes sobre las reservas y preferencias de los usuarios, proporcionando información valiosa para la toma de decisiones empresariales.

#### Experiencia Mejorada
La aplicación ofrece una presentación visual atractiva de los tours, ayudando a las empresas a destacarse en el mercado.

## Tecnologías Utilizadas

#### Symfony 6

Utilizamos Symfony, un framework de desarrollo web de código abierto, para crear la arquitectura robusta y escalable de la aplicación.

#### PHP 8.2

Utilizamos PHP como lenguaje principal en el backend para implementar la lógica de negocio y gestionar las solicitudes y respuestas HTTP.

#### Doctrine ORM

Implementamos el ORM de Doctrine para la gestión eficiente de la base de datos, permitiendo operaciones CRUD de manera sencilla.

#### Twig y HTML

Utilizamos Twig para la generación dinámica de las vistas HTML, permitiendo una separación efectiva de la lógica de presentación del código PHP.

#### MySQL

Empleamos MySQL como sistema de gestión de bases de datos relacional para almacenar y recuperar la información necesaria.

#### OpenStreetMap API

Integramos la API de OpenStreetMap para implementar la funcionalidad de vista de mapa y selección de ubicaciones.

## -Colaboración y Herramientas

Para el desarrollo colaborativo de este proyecto, utilizamos Git y GitHub como sistema de control de versiones y plataforma de colaboración. Implementamos el flujo Git Flow, con la rama "dev" como principal y ramas derivadas para diferentes funcionalidades.

## -Requisitos, Instalación y Uso

### Requisitos
Necesitas tener instalado lo siguiente:
Symfony CLI 5.4.20 o superior.
Symfony 6.2.0    
php 8.2.0
MySQL 8.0.31


#### 1/ Crea una carpeta con el nombre que quieras dar al proyecto


#### 2/ Clona este repositorio en tu máquina local
Desde la raiz del proyecto en la consola ejecuta lo siguiente: git clone git@github.com:EduGese/TourNow.git

#### 3/ Instalar Composer
Asegúrate de tener Composer instalado. Composer es una herramienta esencial para gestionar las dependencias de PHP.

Descarga e instala Composer: https://getcomposer.org/download/

#### 4/ Instalar Dependencias
Desde la carpeta raíz del proyecto, ejecuta el siguiente comando para instalar las dependencias del proyecto definidas en composer.json:  composer install

#### 5/ Base de datos 
Desde cualquier gestor MySql:
Crea una base de datos llamada "ftc_db"
Importa en esa base de datos el archivo .......
En el archivo del proyect  .env copia esto en la linea correspondiente: DATABASE_URL="mysql://user:pass@127.0.0.1:3306/fct_db?serverVersion=8.0.31"

Donde user es el nombre usuario y pass es la contraseña de acceso a la base de datos



Asegúrate de tener Symfony instalado en tu entorno.
Configura la base de datos en el archivo "parameters.yml".
Ejecuta las migraciones para crear las tablas en la base de datos.
Inicia el servidor local de Symfony.

## -Contribución

Este proyecto es de código abierto y las contribuciones son bienvenidas. Si deseas contribuir, realiza un fork del repositorio, crea una rama para tu funcionalidad y luego crea un pull request.

## -Licencia

Este proyecto está bajo la Licencia MIT - consulta el archivo LICENSE para más detalles.
