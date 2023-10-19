
# TOURNOW  - Plataforma de Reservas de Actividades Turísticas


# TOURNOW - Tourist Activity Booking Platform

## Project Description

- [Description](#description)
- [Screenshots](#screenshots)

## Key Features

- [Register and Explore](#register-and-explore)
- [Tour Details](#tour-details)
- [Customized Booking](#customized-booking)
- [Reviews and Ratings](#reviews-and-ratings)
- [Booking Tracking](#booking-tracking)
- [Business Profiles](#business-profiles)
- [Centralized Management](#centralized-management)
- [Enhanced User Experience](#enhanced-user-experience)

## Technologies Used

- [Symfony 5](#symfony-5)
- [PHP 8.2](#php-8.2)
- [Doctrine ORM](#doctrine-orm)
- [Twig and HTML](#twig-and-html)
- [MySQL](#mysql)
- [OpenStreetMap API](#openstreetmap-api)

## Collaboration and Tools

- [Collaboration and Tools](#collaboration-and-tools)

## Requirements, Installation, and Execution

- [Requirements](#requirements)
- [Installation](#installation)
- [Execution](#execution)

## Contribution

- [Contribution](#contribution)

## License

- [License](#license)


## Descripción del Proyecto

TourNow es una aplicación web que busca revolucionar la forma en que las empresas turísticas promocionan y gestionan sus tours, y cómo los viajeros exploran y reservan actividades turísticas en las ciudades de Madrid, Barcelona y Sevilla. La plataforma brinda a las empresas la oportunidad de crear perfiles, describir en detalle sus tours, especificar fechas, ubicaciones y precios, y exhibir imágenes atractivas para captar la atención de los clientes potenciales. Por otro lado, los usuarios pueden explorar una variedad de actividades, visualizar detalles importantes de cada tour y realizar reservas de manera conveniente y personalizada.

![TourNow](https://github.com/EduGese/TourNow/assets/122921699/ba563f48-ab8d-4ce4-80db-c11fa36087d9)



## Características Principales

#### Regístrate y Explora
Los usuarios pueden registrarse en la plataforma y explorar una amplia gama de tours disponibles en las ciudades seleccionadas. Registros separados para clientes y para empresas:
Clientes:
![userRegister](https://github.com/EduGese/TourNow/assets/122921699/d5da9ef9-a289-486d-af7d-84a9fe5a22b2)
Empresas:
![adminregister](https://github.com/EduGese/TourNow/assets/122921699/e991a177-e5ae-4271-8863-57d98577b995)





#### Detalles de Tours
Los usuarios pueden ver detalles completos de cada tour, incluyendo ubicaciones, horarios, precios, descripciones y plazas disponibles.
![detalleAct1](https://github.com/EduGese/TourNow/assets/122921699/38fef4b8-e960-4df9-918e-4a4d86abe491)

![detalleAct2](https://github.com/EduGese/TourNow/assets/122921699/9ddb80fa-1412-4d08-86f4-973f2d99cbc6)

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

#### Experiencia Mejorada
La aplicación ofrece una presentación visual atractiva de los tours, ayudando a las empresas a destacarse en el mercado.

## Tecnologías Utilizadas


#### Symfony 5

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


## Colaboración y Herramientas

Para el desarrollo colaborativo de este proyecto, utilizamos Git y GitHub como sistema de control de versiones y plataforma de colaboración. Implementamos el flujo Git Flow, con la rama "dev" como principal y ramas derivadas para diferentes funcionalidades.

## Requisitos, Instalación y Ejecución

### Requisitos

#### Necesitas tener instalado lo siguiente:

Symfony CLI 5.4.20 o superior.

Symfony 6.3.0  --> https://symfony.com/doc/current/setup.html

php 8.2.0 --> https://www.php.net/manual/en/install.php

MySQL 8.0.31  -->  https://www.mysql.com/downloads/


### Instalaciones

 #### 1/ Crea una carpeta con el nombre que quieras dar al proyecto


####  2/ Clona este repositorio en tu máquina local
Desde la raiz del proyecto en la consola ejecuta lo siguiente: 
````git clone git@github.com:EduGese/TourNow.git````

 #### 3/ Instalar Composer
Asegúrate de tener Composer instalado. Composer es una herramienta esencial para gestionar las dependencias de PHP.

Descarga e instala Composer: https://getcomposer.org/download/

 #### 4/ Instalar Dependencias
Desde la carpeta raíz del proyecto, ejecuta el siguiente comando para instalar las dependencias del proyecto definidas en composer.json:  composer install

 #### 5/ Base de datos 

Desde cualquier gestor MySql:

Crea una base de datos llamada "ftc_db"

Importa en esa base de datos el archivo fct_db.sql

En el archivo del proyect  .env copia esto en la linea correspondiente: DATABASE_URL="mysql://user:pass@127.0.0.1:3306/fct_db?serverVersion=8.0.31"

Donde user es el nombre usuario y pass es la contraseña de acceso a la base de datos.

### Ejecución

#### Abre tu gestor de base de datos y haz login.
#### Levantar el servidor local de Symfony:

 Colócate en la raíz del proyecto (ya sea con la consola de tu SO o con la terminal de VSCode) 

 Ejecuta:
 ````Symfony server:start````

 En el navegador pega la siguiente url --> http://localhost:8000/home , esto abrirá la página de inicio. 

 Ya puedes disfrutar de TourNow


## Contribución

Este proyecto es de código abierto y las contribuciones son bienvenidas. Si deseas contribuir, realiza un fork del repositorio, crea una rama para tu funcionalidad y luego crea un pull request.

## Licencia

Este proyecto está bajo la Licencia MIT - consulta el archivo LICENSE para más detalles:

https://github.com/EduGese/TourNow/blob/dev/LICENSE.md
