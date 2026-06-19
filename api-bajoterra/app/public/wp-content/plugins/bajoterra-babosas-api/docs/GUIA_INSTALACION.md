# Guia paso a paso - Plugin API Bajoterra

## 1. Copiar la carpeta del plugin

1. Entra a la carpeta del sitio local:
   `api-bajoterra/app/public/wp-content/plugins/`
2. Verifica que exista la carpeta:
   `bajoterra-babosas-api`
3. Esa carpeta completa es la que se puede comprimir en `.zip` para entregar.

## 2. Activar el plugin en WordPress

1. Abre el sitio en Local.
2. Entra al panel de WordPress.
3. Ve a **Plugins > Plugins instalados**.
4. Busca **API Bajoterra - Babosas Interactivas**.
5. Presiona **Activar**.

## 3. Crear la pagina donde se vera el directorio

1. Ve a **Paginas > Anadir nueva**.
2. Escribe un titulo, por ejemplo: `Babosas de Bajoterra`.
3. Agrega un bloque **Shortcode**.
4. Pega este shortcode:

```text
[bajoterra_babosas]
```

5. Publica o actualiza la pagina.
6. Abre la pagina en el frontend.

## 4. Probar la interaccion

En la pagina publicada debes poder:

1. Ver las tarjetas de babosas.
2. Ver una imagen local distinta para cada babosa.
3. Buscar por nombre, tipo, elemento o poder.
4. Filtrar por elemento.
5. Dar clic en una tarjeta para abrir mas informacion con imagen grande.
6. Cerrar el modal con el boton `x` o presionando `Esc`.
7. Usar el boton **Actualizar** para consultar nuevamente el contexto desde la API publica.

## 5. Endpoint REST creado por el plugin

El plugin registra esta ruta local:

```text
/wp-json/bajoterra/v1/babosas
```

Ejemplos:

```text
/wp-json/bajoterra/v1/babosas?search=burpy
/wp-json/bajoterra/v1/babosas?element=Fuego
/wp-json/bajoterra/v1/babosas?refresh=true
```

## 6. Que API publica usa

No se encontro una API publica oficial dedicada a las babosas de Bajoterra. Para cumplir el requisito de consumo de API REST publica con `wp_remote_get()`, el plugin consulta el resumen publico de Slugterra/Bajoterra desde Wikipedia REST API:

```text
https://es.wikipedia.org/api/rest_v1/page/summary/Slugterra
```

Los datos especificos de babosas se exponen desde una REST API propia del plugin para que la experiencia sea estable en WordPress.

## 7. Recomendaciones para el video

1. Muestra la carpeta del plugin dentro de `wp-content/plugins`.
2. Activa el plugin desde WordPress.
3. Crea una pagina con el shortcode `[bajoterra_babosas]`.
4. En el frontend, demuestra busqueda, filtros, clic en tarjeta y boton de actualizar.
5. Abre la vista movil del navegador para mostrar que el diseno es responsive.
