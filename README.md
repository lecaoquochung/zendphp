# BASIC ZENDPHP
- Development environment for ZENDPHP with DOCKER COMPOSE
- [x] Setup environment
- [x] Init Zend framework
- [ ] Project 01: Album Application
- [ ] Project 02: Blog Application

## Setup environment
- Docker for windows https://docs.docker.com/docker-for-windows/
- Docker for mac https://docs.docker.com/docker-for-mac/

### Clone this repository
```
git clone https://github.com/lecaoquochung/zendphp.git
cd zendphp
docker-compose up

# /etc/hosts
127.0.0.1 zendphp.dev
127.0.0.1 zendphp-album.dev
```

- Go to <http://zendphp.dev/> and you can see PHP works.
- Go to <http://zendphp.dev/zf> and you can see ZF PHP works.

### Command (./docker.sh)
- `mysql -u zendphp -p zendphp -D zendphp -h 127.0.0.1`
- `docker-compose ps` shows the status of containers
- `docker exec -it zendphp_server_1 bash` enter the shell of a container
- `mysql -u zendphp -p zendphp -D zendphp -h 127.0.0.1` enter the MySQL console

## Init Zend framework
### Download composer
- Download Latest composer.phar https://getcomposer.org
```
wget https://getcomposer.org/composer.phar
```

### Zend framework

## Project 01: Album Application
- [ ] Init skeleton
- [ ] Album module
- [ ] Routing module album
- [ ] Database & Model for module Album

### Init
```
./composer.phar create-project -s dev zendframework/skeleton-application album
```
- Reference
 - https://docs.zendframework.com/tutorials/getting-started/skeleton-application/

### Album module
- Step 1: Module structure
```
/module
        /Album
            /config
            /src
                /Controller
                /Form
                /Model
            /view
                /album
                    /album
```

- Step 2: create module/Album/src/Module.php
```
<?php
namespace Album;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
```

- Step 3: Autoload module with composer.json
```
"autoload": {
    "psr-4": {
        "Application\\": "module/Application/src/",
        "Album\\": "module/Album/src/"
    }
},

./composer.phar dump-autoload
```

- Step 4: Configuration
 - /module/Album/config/module.config.php
```
<?php
namespace Album;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\AlbumController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];
```

 - config/modules.config.php
```
return [
    'Zend\Form',
    'Zend\Db',
    'Zend\Router',
    'Zend\Validator',
    'Application',
    'Album',          // <-- Add this line
];
```

- Reference
 - https://docs.zendframework.com/tutorials/getting-started/modules/
 - https://github.com/lecaoquochung/album/commit/19ef562e87913d1188f2e46ef714375f6a1f9d6f

### Routing module Album
- Create route for module album configmodule.config.php
```
'router' => [
        'routes' => [
            'album' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
```
- Controller module/Album/src/Controller/
```
<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    public function indexAction()
    {
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
```

- View
```
module/Album/view/album/album/index.phtml
module/Album/view/album/album/add.phtml
module/Album/view/album/album/edit.phtml
module/Album/view/album/album/delete.phtml
```
- Reference
 - https://docs.zendframework.com/tutorials/getting-started/routing-and-controllers/

- Commit
 - https://github.com/lecaoquochung/album/commit/18d83570d6b62fa1bc8fb6e0c359a0adfe8297ab

## Database & Model for module Album
- db/init.d/schema.sql

- module/Album/src/Model/Album.php
```
<?php
namespace Album\Model;

class Album
{
    public $id;
    public $artist;
    public $title;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->artist = !empty($data['artist']) ? $data['artist'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
    }
}
```

- module/Album/src/Model/AlbumTable.php
```
<?php
namespace Album\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AlbumTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = [
            'artist' => $album->artist,
            'title'  => $album->title,
        ];

        $id = (int) $album->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getAlbum($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
```

- module/Album/src/Module.php
```
...
public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
...
```

- config/modules.config.php (global)
```
'Zend\Form',
'Zend\Db',
```

- AlbumController
```
...
// Add this property:
    private $table;

    // Add this constructor:
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }
...
```

- Album/Module.php
```
...
// getConfig() and getServiceConfig methods are here

public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class)
                    );
                },
            ],
        ];
    }
...
```

- Reference
 - https://docs.zendframework.com/tutorials/getting-started/database-and-models/
