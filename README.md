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

- Commit
 - https://github.com/lecaoquochung/album/commit/19ef562e87913d1188f2e46ef714375f6a1f9d6f
 - https://github.com/lecaoquochung/zendphp/commit/076aece0a0de0556fb4e85593cb1ee3a88984177 (It works)

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
 - https://github.com/lecaoquochung/album/commit/18d83570d6b62fa1bc8fb6e0c359a0adfe8297ab -> (Error)
 - https://github.com/lecaoquochung/zendphp/commit/7dbb2eb50e5cb2d6009791e67fcc222c66111ae2 (It works)

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

    // construct table model
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

- Commit https://github.com/lecaoquochung/zendphp/commit/75a4ff480ff71bd5d0bcd3593d7745c1d8680199 (Error)
```
Error: construct model in controller
```

## Project 02: Blog Application

### Init module Blog
```
module/
    Blog/
        config/
        src/
        view/
```

- Composer autoload, call new module
```
"autoload": {
   "psr-4": {
        "Application\\": "module/Application/src/",
        "Album\\": "module/Album/src/",
        "Blog\\": "module/Blog/src/"
   }
}

composer dump-autoload
```
- Module namespace & config Module.php
```
<?php
namespace Blog;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
```

- Blog config file module/Blog/config/module.config.php
```
<?php
// In /module/Blog/config/module.config.php:
namespace Blog;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases' => [
            Model\PostRepositoryInterface::class => Model\PostRepository::class,
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
        ],
    ],

    // controller
    'controllers' => [
        'factories' => [
            // Controller\ListController::class => InvokableFactory::class,
            Controller\ListController::class => Factory\ListControllerFactory::class,
        ],
    ],

    // This lines opens the configuration for the RouteManager
    'router' => [
        // Open configuration for all possible routes
        'routes' => [
            // Define a new route called "blog"
            'blog' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/blog" as uri:
                    'route' => '/blog',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    // view
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
```

- module/Blog/src/Controller/ListController.php
```
<?php
// In module/Blog/src/Controller/ListController.php:
namespace Blog\Controller;

use Blog\Model\PostRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
// Add the following import statement:
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    // Add the following method:
    public function indexAction()
    {
        return new ViewModel([
            'posts' => $this->postRepository->findAllPosts(),
        ]);
    }
}
```

- module/Blog/view/blog/list/index.phtml
```
<!-- Filename: module/Blog/view/blog/list/index.phtml -->
<h1>Blog\ListController::indexAction()</h1>
```

### Blog Model and ServiceManager
- module/Blog/src/Model/PostRepositoryInterface.php
```
<?php
namespace Blog\Model;

interface PostRepositoryInterface
{
    /**
     * Return a set of all blog posts that we can iterate over.
     *
     * Each entry should be a Post instance.
     *
     * @return Post[]
     */
    public function findAllPosts();

    /**
     * Return a single blog post.
     *
     * @param  int $id Identifier of the post to return.
     * @return Post
     */
    public function findPost($id);
}
```

- module/Blog/src/Model/PostRepository.php
```
<?php
namespace Blog\Model;

class PostRepository implements PostRepositoryInterface
{
    private $data = [
        1 => [
            'id'    => 1,
            'title' => 'Hello World #1',
            'text'  => 'This is our first blog post!',
        ],
        2 => [
            'id'    => 2,
            'title' => 'Hello World #2',
            'text'  => 'This is our second blog post!',
        ],
        3 => [
            'id'    => 3,
            'title' => 'Hello World #3',
            'text'  => 'This is our third blog post!',
        ],
        4 => [
            'id'    => 4,
            'title' => 'Hello World #4',
            'text'  => 'This is our fourth blog post!',
        ],
        5 => [
            'id'    => 5,
            'title' => 'Hello World #5',
            'text'  => 'This is our fifth blog post!',
        ],
    ];

    /**
    * {@inheritDoc}
    */
   public function findAllPosts()
   {
       return array_map(function ($post) {
           return new Post(
               $post['title'],
               $post['text'],
               $post['id']
           );
       }, $this->data);
   }

   /**
    * {@inheritDoc}
    */
   public function findPost($id)
   {
       if (! isset($this->data[$id])) {
           throw new DomainException(sprintf('Post by id "%s" not found', $id));
       }

       return new Post(
           $this->data[$id]['title'],
           $this->data[$id]['text'],
           $this->data[$id]['id']
       );
   }
}
```

- module/Blog/src/Model/Post.php
```
<?php
namespace Blog\Model;

class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $title
     * @param string $text
     * @param int|null $id
     */
    public function __construct($title, $text, $id = null)
    {
        $this->title = $title;
        $this->text = $text;
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
```

- /module/Blog/src/Factory/ListControllerFactory.php
```
<?php
// In /module/Blog/src/Factory/ListControllerFactory.php:
namespace Blog\Factory;

use Blog\Controller\ListController;
use Blog\Model\PostRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ListController($container->get(PostRepositoryInterface::class));
    }
}
```

- module/Blog/view/blog/list/index.phtml
```
<!-- Filename: module/Blog/view/blog/list/index.phtml -->
<h1>Blog</h1>

<?php foreach ($this->posts as $post): ?>
<article>
  <h1 id="post<?= $post->getId() ?>"><?= $post->getTitle() ?></h1>

  <p><?= $post->getText() ?></p>
</article>
<?php endforeach ?>
```

### Reference
- Blog Module https://docs.zendframework.com/tutorials/in-depth-guide/first-module/
- Blog Model and ServiceManager https://docs.zendframework.com/tutorials/in-depth-guide/models-and-servicemanager/
