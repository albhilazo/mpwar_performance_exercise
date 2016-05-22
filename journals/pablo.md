## Diario de Pablo ##

 1 - **Problema** de permisos con el módulo **apt** al intentar agregar un repositorio para poder instalar PHP 5.6. Se **soluciona** haciendo que se ejecute como sudo mediante la instrucción **become** a *yes*.
 
 2 - **Problema** con Apache y PDO, la aplicación soltaba un error por no encontrar el driver a pesar de estar correctamente instalado. Se **soluciona** reiniciando el servidor web.
 
 3 - **Problema** al añadir la librería Predis a composer. Causaba problemas al intentar guardar en Redis elementos de sesión pues la aplicación no encontraba un handler capaz de serializar las sesiones. La **solución** fue añadir a el DomainProvider el handler que proporciona la librería Predis y modificar el orden de registro de los providers para que el del dominio Performance fuera el último.
 
```php
// \Performance\DomainServiceProvider::register
$app['session.storage.handler'] = function () use ($app) {
           return new \Predis\Session\Handler($app['predis.client']);
};
```

```php
// src/Performance/app.php
$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider);
$app->register(new DoctrineOrmServiceProvider);
$app->register(new Performance\DomainServiceProvider());
```

 4 - **Problema** al generar los rankings en Redis pues al usar listas ponderadas, si se usa el objeto serializado como clave, si se edita el artículo, la clave cambia y el ranking se tergiversa. La **solucion** fue hacer que las claves fueran los IDs de los artículos (que no cambian) y que para obtener el contenido de ellos, se realicen consultas a la base de datos.

 5 - **Problema** al intentar añadir los headers de cache pues Silex no reconocía los parámetros por defecto establecidos en el app.php. Se **soluciona** añadiendo en header en cada objecto *Response*.
 
```php
return new Response(
  $this->template->render('article.twig', ['article' => $article]),
  200,
  [ 'Cache-Control' => 's-maxage=300, private' ]
);
```

 6 - **Problema** al intentar conectar la aplicación a la base de datos remota. Era debido a que no había ningún usuario configurado para acceder a ella de forma remota. La **solución** es añadir un nuevo usuario MySQL para la aplicación.

 7 - Por una previsión de un posible problema en la máquina, se limita por configuración la memoria asignada a Redis. 
