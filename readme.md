<p align="center">
<a href="https://www.dev2bit.com">
  <img width="200" alt="dev2bit"  src="https://raw.githubusercontent.com/fbohorquez/sql-charts-dashboard/master/resources/logo.png"/>
</a>
</p>
# WordPress Templates: wpt

wpt es un sistema para crear plantillas de proyectos WordPress.

Con wpt es posible crear plantillas de proyectos Wordpress. Con la plantilla se podrá establecer el código báse y las dependencias que tendrá inicialmente cada proyecto. Por ejemplo, es posible crear una plantilla de ecommerce, que incluya woocommerce, algunos otros plugins útilizados en este tipo de proyectos, un tema para tiendas, algún código css propio, etc.  De esta forma con un simple comando se puede crear una tienda totalmente funcional, ahorrando muchísimo tiempo. Además de todo los aspectos de la aplicación, la plantilla también puede predefinir los servicios software que utilizará el proyecto y su configuración. Por ejemplo cómo debe estar configurado el servidor web, o si necesita de un servidor de chat.

Los proyectos creados con plantillas wpt pueden ser ejecutados en un entorno virtual, en el que se lleva a cabo cada servicio, aplicándose las configuraciones establecidas para cada uno. Con un simple comando se puede ejecutar el proyecto completo, incluido todos los servicios software necesarios. Todo esto de forma independientemente la plataforma en la que se ejecute, pudiéndo ser en una única máquina o de forma distribuida en un cluster de servidores.

Los proyectos construidos sobre plantillas wpt son:

* Completos: El proyecto contendrá tanto el código y los datos de la aplicación, como la configuración de todos los servidores que son necesarios para su ejecución.
* Independientes: Al sistema operativo y las aplicaciones que este tenga instaladas.
* Escalables: Los proyectos pueden ser ejecutados en entornos distribuidos independientemente del número de máquinas que los conformen.
* Autoejecutables: Con un simple comando el proyecto se puede ejecutar en una sola máquina local o en varios servidores.
* Portables: Puede ejecutarse independientemente de la plataforma.
* Extensibles: Es fácil añadir más servicios y dependencias.
* Configurables: Según el entorno en el que se ejecute y para cada instancia del mismo.
* Modificaciones desde la plantilla: Al aplicar cambios a una plantilla, los proyectos creados a partir de estas pueden actualizar estas modificaciones facilmente.
* Actualizables: Tanto el código de WordPress como el de todas las dependencias (plugins, temas, traducciones...)  se deben actualizar fácilmente

## Tecnologías usadas

* git: Para el control de versiones
* composer: Para la gestión de dependencias
* docker: Para la gestión de contenedores

## Requisitos

* git
* docker y docker-compose

## Plantillas de proyectos WordPress
Las plantillas creadas con wpt son repositorios git, los cuales definen un entorno de contenedores docker sobre el que se ejecutarán la aplicaciones. Además definen las dependencias de base (plugins, temas, traducciones...) que tendrán los proyectos que se creen.

A partir del repositorio wpt es posible crear otro repositorio, donde las dependencias y los servicios estén totalmente personalizados. Este nuevo repositorio es el que se corresponde con
la plantilla y es el que a su vez se utilizará para crear otros repositorios de proyectos.


## Arquitectura

Un proyecto wpt se compone del código de aplicación (src) y de la información de despliegue (deploy).

### Código de aplicación (src)

Inicialmente este directorio contiene un fichero de definición para composer, el cual guarda las dependencias que tendrán todos los proyectos creados por la plantilla.
Es posible editar este fichero para añadir sus propios plugins, temas y traducciones

### Información de despliegue (deploy)

Un proyecto wpt se compone de una serie de servicios que se ejecutan en unos contenedores docker. Los servicios se han dividido en dos grupos:

* core: Servicios necesarios para el correcto funcionamiento de cualquier proyecto WordPress
* services: Servicios complementarios que se podrán usar en función el proyecto

Dentro de core se encuentran la definición de servicios básicos tales como servidor web, el servidor de script o la base de datos. Además también contiene dos servicios especiales, el servicio proxy y el dev, los cuales se detallarán más adelante.

El directorio services contiene los servicios complementarios, cuyo uso dependerá del proyecto o el entorno en que se ejecute. Un servicio complementario puede ser por ejemplo phpmyadmin para la gestión cómoda de la base de datos, o mailtrap para depurar el envío de emails. Cada plantilla puede definir sus propios servicios y cada instancia de un mismo proyecto puede definir los servicios que utilizará. Un proyecto podría necesitar de un servicio que se encargue de realizar backups automáticos, pero este servicio solo será necesario en la instancia del proyecto
que se ejecuta en el entorno de producción.

Cada servicio se configura mediante un fichero docker-compose, y dependiendo el caso, de un DockerFile y de los ficheros de configuración que se montarán en el contenedor.

## Entornos de ejecución
Un projecto wpt puede ejecutarse en dos entornos:

* dev: Entorno de desarrollo
* prod: Entorno de producción

Esto permite definir unos parámetros diferentes para los servicios que se ejecutan dependiendo el caso. Así, de forma predeterminda en el entorno de desarrollo se crea un contenedor
de base de datos, pero en el de producción no. El usuario en sus plantillas puede redefinir los servicios básicos que se ejecutan en ambos entornos mediante los ficheros docker-compose
correspondientes:

* docker-compose.yml: Fichero general que define los servicios presentes en ambos entornos
* docker-compose.dev.yml: Fichero que define los servicios que se ejecutarán en el entorno de desarrollo
* docker-compose.prod.yml: Fichero que define los servicios que se ejecutarán en el entorno de producción

## Variables de entorno
Cada proyecto wpt consta de un fichero de configuración de varaibles que tiene que ser definido para cada instancia de proyecto, y en donde se establece los valores de los parámetros de configuración
que se usarán en cada contenedor y en el código de la aplicación. Estas variables dependerán de los servicios utilizados, aúnque muchas de ellas son de definición obligatoria para el correcto funcionamiento del proyecto, como por ejemplo el nombre de la aplicación, el host o los datos de conexión de la base de datos. Se dispone de un fichero de ejemplo .env.example
que vale como plantilla para definir estas variables.

## Crear una plantilla de proyecto WordPress
Para crear una plantilla de proyecto WordPress lo primero es clonar el repositorio git de wpt

```bash
$ git clone $WPT_REPO my-template
$ cd my-template
```
La plantilla es en si misma un repositorio git, por lo que se precisa de un repositorio vacío.

Al ejecutar el script wpt con la accion init y la url del repositorio donde se desea guardar la plantilla, se transfiere todo el código de wpt al nuevo repositorio como un solo commit.

```bash
$ ./wpt init $MY_TEMPLATE_REPO
```

El nuevo repositorio representa la plantilla de proyectos. Es posible editar todos los aspectos del sistema wpt para desarrollar plantillas ajustadas a las necesidades y preferencias concretas. Por ejemplo, es posible añadir dependencias de plugins con composer o añadir nuevos servicios en forma de  contenedores docker mediante docker-compose. Tras editar la plantilla y hacer push los cambios se subirán al repositorio de la plantilla.


## Crear un proyecto desde una plantilla wpt
Para crear un proyecto desde una plantilla wpt lo primero es clonar el repositorio de la plantilla wpt.

```bash
$ git clone $MY_TEMPLATE_REPO my-project
$ cd my-project
```

El proyecto es en si mismo un repositorio git, por lo que se precisa un repositorio vacío.

Al ejecutar el script wpt con la acción new y la url del repositorio donde se desea guardar el proyecto, se transfiere todo el código de la plantilla al repositorio del proyecto.

```bash
$ ./wpt new $MY_PROJECT_REPO
```
En este caso se mantiene todos los commits de la plantilla y hace que el proyecto se pueda
actualizar desde esta. Esta opción crea un enlace remoto llamado "origin" que apunta al repositorio del proyecto, y un enlace remoto llamado "upstream" que apunta al repositorio de la plantilla. De esta forma cada proyecto puede ser actualizado desde la plantilla.

Para actualizar un proyecto desde la plantilla se puede utilizar wpt con la acción pull.

```bash
$ ./wpt pull
```

Es posible crear una plantilla desde otra, de la misma forma que se crea un proyecto.

## Ejecutar el proyecto

El script wpt permite ejecutar el proyecto.

* Arranca los servicios básicos para el funcionamiento del mismo
* Arranca los servicos adicionales
* Asocia el servicio web a un proxy en su misma red virtual
* Resuelve dependencias no satisfechas desde el servicio dev

```bash
$ ./wpt
```

El proyecto se ejecuta sobre un entrono de contenedores dockers, gestionados internamente mediante docker-compose. El script wpt carga las variables de entorno definidas en el fichero .env y ejecuta docker-compose con los ficheros yml correspondiente a cada entorno (dev y prod) y a cada servicio adicionales

El script wpt es como docker-compose, pero con algunas acciones adicionales, y donde muchas de las opciones son implicitas gracias al fichero de variable de entorno. Es posible ejecutar cualquier acción de docker-compose mediante wpt, aplicandose esta sobre los servicios especificados en el proyecto.

Así se puede de decir que estas dos líneas son equivalentes:
```bash
$ ./wpt ACTION ARGS
$ docker-compose OPTIONS_IN_.ENV ACTION ARGS
```

Los valores del .env básicamente define las opciones que le dice a docker-compose los ficheros de configuración que debe tomar para crear los servicios que necesita la instancia concreta del proyecto.

Ejecutar el script wpt sin argumentos es equivalente a:

```bash
$ ./wpt up -d
```
Para más información sobre las acciones que soporta docker-compose se puede recurrir a la ayuda de este.

## Servicio proxy

Todo proyecto wpt debe estar conectado con un servicio proxy desde su servicio web y por medio de una red virtual. El servcicio proxy es el que escucha en los puertos de la máquina donde se ejecuta el proyecto, y es el encargado de redireccionar las peticiones correspondientes a cada proyecto.

Gracias al servicio proxy es posible tener varios proyectos wpt cada uno con su propio servicio web, pero todos respondiendo peticiones en el mismo puerto de la maquina. Así se puede tener un servicio proxy escuchando en el puerto 80, con dos o más proyectos conectados al proxy, de forma que cuando el proxy reciba una petición la redireccionará al proyecto que se corresponda con el hostname de la misma.  

Dos o más proyectos wpt se pueden conectar al mismo proxy si se conectan a la misma red virtual. Por defecto todo projecto wpt se conecta a una red virtual llamada "wpt", pero es posible establecer la red a la que se conecta un proyecto mediante la variable de entorno NETWORK

Al ejecutarse un proyecto wpt se comprueba si existe un proxy funcionando en la red a la que se conecta, y si no es así se ejecuta un servicio proxy en dicha red. El proxy creado escuchará en los puertos indicados por las variables de entorno WEB_PORT y WEB_SSL_PORT, cuyos valores por defecto son 80 y 443 respectivamente. Si el proxy ya está creado el proyecto se asocia a este independientemente de los puertos en los que escuche.

## Servicio dev

Este servicio incluye todas las herramientas de desarrollo necesarias para el proyecto. Por ejemplo es el servicio que tiene instalado composer para la gestión de dependencias.

Mediante el script wpt y la acción dev podemos ejecutar comandos dentro de este servicio, siendo el directorio de trabajo el directorio del código de la aplicación

Por ejemplo al ejecutar el siguiente comando se actualizarán todas las dependencias de la aplicación.

```bash
$ ./wpt dev composer update
```

Además wpt incluye algunas acciones como alias, lo que permiten gestionar las dependencias desde el servicio dev de forma fácil.

```bash
$ ./wpt composer [ACTION]
$ ./wpt dev composer [ACTION]
```
```bash
$ ./wpt require [DEPEND]
$ ./wpt dev composer require [DEPEND]
```
El servicio dev se inicia automáticamente cada vez que se ejecuta el proyecto. Cada vez que se inicia este servicio se llevan a cabo los script bash que se encuentran en el directorio "deploy/core/dev/run.d". Inicialmente este directorio contiene un script que se encarga de comprobar que las dependencias esten satisfechas. De esta forma cada vez que se ejecuta el proyecto se comprueban las dependencias, instalándose estas la primera vez.


## Añadir una nueva dependencia a la plantilla
La gestion de dependencias es tratada con composer desde el servicio dev. Gracias al sistema de gestión de dependencias es posible añadir plugins, temas, traducciones y demás recursos fácilmente, además de facilitar su actualización

Los paquetes se toman de

## Autor

Francisco Javier Bohórquez Ogalla

Developed with ♥ by [dev2bit](https://www.dev2bit.com)
