### Installations


add to routes/web.php
```php
$router->post('/user/todo/select', 'TodoController@select');
$router->post('/user/todo/view', 'TodoController@view');
$router->post('/user/todo/add', 'TodoController@add');
$router->post('/user/todo/edit', 'TodoController@edit');
$router->post('/user/todo/delete', 'TodoController@delete');
$router->post('/user/todo/autocomplete', 'TodoController@autocomplete');
```
